<?php

namespace OM\CC\Shared\Lib\Common;

use OM\CC\Shared\Lib\Assets\Assets;
use OM\CC\Shared\Lib\System\Singleton;
use OM\CC\Shared\Lib\Utils\Shortcodes;
use OM\CC\Shared\Lib\Utils\Utils;

abstract class BaseElement extends Singleton {
	
	/**
	 * @var string -- Tag name
	 */
	public $tag;
	
	/**
	 * @var array -- Shortcode instance settings
	 */
	public $settings;
	
	/**
	 * @var array -- Shortcode parameters
	 */
	public $parameters;
	
	/**
	 * @var string -- shortcode instance hash
	 */
	public $hash;
	
	/**
	 * @var string Shortcode content
	 */
	public $content;
	
	/**
	 * @var string
	 */
	protected $_version;
	
	/**
	 * @var string
	 */
	protected $_stylesUrl;
	
	/**
	 * @var string
	 */
	protected $_scriptUrl;
	
	/**
	 * @var array
	 */
	protected $_presets;
	
	/**
	 * @var string Store plugin directory
	 */
	private $directory;
	
	/**
	 * @var integer -- current edited post id
	 */
	private static $editPostId;
	
	/**
	 * Shortcode initiation
	 */
	abstract public function init();
	
	/**
	 * Base constructor.
	 *
	 * @param string $tag
	 */
	protected function __construct($tag = null) {
		parent::__construct();
		
		if ($tag) {
			$this->tag = $tag;
		} else {
			$classString = str_replace(
				array('\\', 'OM_CC_Plugins'),
				array('_', 'om_cc_vc'),
				get_class($this));
			
			$this->tag = strtolower(preg_replace('/([a-zA-Z])([A-Z])/', '$1_$2', $classString));
		}
		
		add_action('edit_post', 'OM\CC\Shared\Lib\Common\BaseElement::__onPostEdit', 1);
		
		add_shortcode($this->tag, array($this, '__render'));
		add_action('vc_before_init', array($this, '__integrate'));
		
		add_filter('vc_base_build_shortcodes_custom_css', array($this, '__customCSS'));
	}
	
	/**
	 * Create Element instance
	 */
	public static function create() {
		$instance = self::getInstance();
		
		$assets = Assets::getInstance();
		$handle = $instance->tag . '_plugin';
		
		if ($instance->_stylesUrl) {
			$assets->registerStyles(array(
				array(
					'handle'  => $handle,
					'source'  => $instance->_stylesUrl,
					'version' => $instance->_version,
					'scope'   => 'wp'
				)
			))->enqueueStyles(array($handle));
		}
		
		if ($instance->_scriptUrl) {
			$assets->registerScripts(array(
				array(
					'handle'       => $handle,
					'source'       => $instance->_scriptUrl,
					'dependencies' => array('jquery'),
					'version'      => $instance->_version,
					'scope'        => 'wp'
				)
			))->enqueueScripts(array($handle));
		}
	}
	
	/**
	 * Hook to run when shortcode is found
	 *
	 * @param array       $attributes
	 * @param null|string $content
	 *
	 * @return string
	 */
	public function __render($attributes, $content = null) {
		$this->settings = $this->_parseSettings($attributes);
		$this->hash     = Shortcodes::hash($this->tag, $this->settings);
		$this->content  = do_shortcode(shortcode_unautop(wpautop(preg_replace('/<\/?p\>/', "\n", $content) . "\n")));
		
		return $this->_getRenderedHtml();
	}
	
	/**
	 * Add shortcode to Visual Composer if exists
	 */
	public function __integrate() {
		$this->init();
		
		vc_map(array_merge(array('base' => $this->tag), $this->parameters));
	}
	
	/**
	 * Remember edit post ID
	 *
	 * @param integer $id Current edit post id
	 */
	public static function __onPostEdit($id) {
		self::$editPostId = $id;
	}
	
	/**
	 * Extend Visual Composer shortcodes CSS styles
	 *
	 * @param string $css Current styles
	 *
	 * @return string updated styles
	 */
	public function __customCSS($css) {
		$shortcodes = $this->_getShortcodes();
		
		$styles = '';
		
		foreach ($shortcodes as $shortcode) {
			$hash     = '.' . $shortcode['hash'];
			$settings = $shortcode['settings'];
			$styles .= $this->_getStyles($hash, $settings);
		}
		
		return $css . Utils::escStyles($styles);
	}
	
	/**
	 * CSS Animation
	 *
	 * @param string $cssAnimation
	 *
	 * @return string
	 */
	public function getCSSAnimation($cssAnimation) {
		if ('' !== $cssAnimation) {
			wp_enqueue_script('waypoints');
			$output = 'wpb_animate_when_almost_visible wpb_' . $cssAnimation;
		} else {
			$output = '';
		}
		
		return $output;
	}
	
	/**
	 * @return array
	 */
	public function getFullSettings() {
		return shortcode_atts(vc_map_get_defaults($this->tag), $this->settings, $this->tag);
	}
	
	/**
	 * @param string $name
	 * @param string $field
	 * @param mixed  $value
	 */
	public function setParamField($name, $field, $value) {
		if ($this->parameters && array_key_exists('params', $this->parameters)) {
			foreach ($this->parameters['params'] as &$param) {
				if ($param['param_name'] === $name) {
					$param[$field] = $value;
				}
			}
		}
	}
	
	/**
	 * Mark visual composer element as container
	 */
	protected function _makeContainer() {
		om_cc_makeVCContainer($this->tag);
	}
	
	/**
	 * Get rendered template HTML
	 * @return string HTML
	 */
	protected function _getRenderedHtml() {
		ob_start();
		include $this->_getTemplate();
		$output = ob_get_contents();
		ob_end_clean();
		
		return $output;
	}
	
	/**
	 * Get current shortcode template
	 * @return string Template path
	 */
	protected function _getTemplate() {
		return $this->_getDir() . '/template.php';
	}
	
	/**
	 * Get additional CSS styles for shortcodes
	 *
	 * @param string $hash
	 * @param array  $settings
	 *
	 * @return string additional styles
	 */
	abstract protected function _getStyles($hash, array $settings);
	
	/**
	 * Get list of shortcodes from page content where tag name is equal to $this->tag
	 * @return array List of shortcodes with parsed settings and hash
	 */
	protected function _getShortcodes() {
		$shortcodes = Shortcodes::parseContent(self::$editPostId, $this->tag);
		
		foreach ($shortcodes as &$shortcode) {
			$settings              = $this->_parseSettings($shortcode['attributes']);
			$shortcode['hash']     = Shortcodes::hash($this->tag, $settings);
			$shortcode['settings'] = $settings;
		}
		
		return $shortcodes;
	}
	
	/**
	 * Add array of files with presets
	 *
	 * @param array $presetFiles Filenames array
	 */
	protected function _setPresets($presetFiles) {
		$tag     = $this->tag;
		$presets = array();
		
		foreach ($presetFiles as $preset) {
			$presets[] = include $preset;
		}
		
		add_action('vc_after_init', function () use ($presets, $tag) {
			foreach ($presets as $preset) {
				do_action('vc_register_settings_preset', $preset['name'], $tag, $preset['settings'], false);
			}
		});
	}
	
	/**
	 * Get current class file directory
	 * @return string
	 */
	private function _getDir() {
		if (!$this->directory) {
			$reflector       = new \ReflectionClass(get_class($this));
			$this->directory = dirname($reflector->getFileName());
		}
		
		return $this->directory;
	}
	
	/**
	 * Parse shortcode attributes
	 *
	 * @param array $attributes List of shortcode attributes
	 *
	 * @return array Entire list of supported attributes and their values
	 */
	private function _parseSettings($attributes) {
		if (!array_key_exists('params', $this->parameters)) {
			return array();
		}
		
		$defaults = array();
		
		foreach ((array)$this->parameters['params'] as $param) {
			$defaults[$param['param_name']] = $this->_getParamDefault($param);
		}
		
		return shortcode_atts($defaults, $attributes);
	}
	
	/**
	 * Get default parameter value
	 *
	 * @param array $parameter
	 *
	 * @return string
	 */
	private function _getParamDefault(array $parameter) {
		if (array_key_exists('std', $parameter)) {
			return $parameter['std'];
		} else {
			$valueDefaults = array('om_cc_checkbox');
			
			return (array_key_exists('value', $parameter) && in_array($parameter['type'], $valueDefaults, true)) ? $parameter['value'] : '';
		}
	}
}
