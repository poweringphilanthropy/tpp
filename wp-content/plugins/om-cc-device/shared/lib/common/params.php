<?php

namespace OM\CC\Shared\Lib\Common;

use OM\CC\Shared\Lib\Data\Options;
use OM\CC\Shared\Lib\Html\HtmlWriter;
use OM\CC\Shared\Lib\System\Singleton;

class Params extends Singleton {
	
	/**
	 * @var array
	 */
	private $_list;
	
	/**
	 * @var string
	 */
	private $_urlBase;
	
	protected function __construct() {
		parent::__construct();
		
		$this->_list = array(
			'om_cc_checkbox'            => array(
				'url'      => 'assets/js/admin/params/checkbox.js',
				'callback' => array($this, '__checkbox')
			),
			'om_cc_code'                => array(
				'url'      => 'assets/js/admin/params/code.js',
				'callback' => array($this, '__code')
			),
			'om_cc_description'         => array(
				'callback' => array($this, '__description')
			),
			'om_cc_inputs'              => array(
				
				'url'      => 'assets/js/admin/params/inputs.js',
				'callback' => array($this, '__inputs')
			),
			'om_cc_map_address'         => array(
				'url'      => 'assets/js/admin/params/map-address.js',
				'callback' => array($this, '__mapAddress')
			),
			'om_cc_number'              => array(
				'callback' => array($this, '__number')
			),
			'om_cc_post_select'         => array(
				'url'      => 'assets/js/admin/params/post-select.js',
				'callback' => array($this, '__postTypes')
			),
			'om_cc_posttype_terms'      => array(
				'url'      => 'assets/js/admin/params/posttype-terms.js',
				'callback' => array($this, '__postTypes')
			),
			'om_cc_posttype_taxonomies' => array(
				'url'      => 'assets/js/admin/params/posttype-taxonomies.js',
				'callback' => array($this, '__postTypes')
			),
			'om_cc_posttype_postfields' => array(
				'url'      => 'assets/js/admin/params/posttype-postfields.js',
				'callback' => array($this, '__postTypes')
			),
			'om_cc_units'               => array(
				'callback' => array($this, '__units')
			),
			'om_cc_color_advanced'      => array(
				'url'      => 'assets/js/admin/color-advanced.js',
				'callback' => array($this, '__colorAdvanced')
			),
		);
		
		add_action('vc_before_init', array($this, '__add'), 0);
	}
	
	/**
	 * @param string $urlBase
	 */
	public function init($urlBase) {
		$this->_urlBase = $urlBase;
	}
	
	/**
	 * Adding Params to Visual Composer
	 */
	public function __add() {
		foreach ($this->_list as $name => $param) {
			$url = array_key_exists('url', $param) ? $this->_urlBase . $param['url'] : null;
			vc_add_shortcode_param($name, $param['callback'], $url);
		}
	}
	
	/**
	 * @param array $settings
	 * @param mixed $value
	 *
	 * @return string
	 */
	public function __checkbox(array $settings, $value) {
		$value  = $value && $value !== 'false';
		$hidden = array(
			'id'    => esc_attr($settings['param_name']),
			'name'  => esc_attr($settings['param_name']),
			'class' => esc_attr("wpb_vc_param_value {$settings['param_name']} {$settings['type']}"),
			'type'  => 'hidden',
			'value' => $value ? 'true' : 'false'
		);
		
		$checkbox = array(
			'type'                => 'checkbox',
			'data-om-cc-checkbox' => esc_attr("#{$settings['param_name']}")
		);
		
		if ($value) {
			$checkbox['checked'] = 'checked';
		}
		
		$html = HtmlWriter::init();
		
		$html->input($hidden, null, true)
		     ->label('class="vc_checkbox-label"')
		     ->input($checkbox, true)->text(" {$settings['label']}");
		
		return (string)$html;
	}
	
	/**
	 * @param array $settings
	 * @param mixed $value
	 *
	 * @return string
	 */
	public function __code(array $settings, $value) {
		$value = htmlspecialchars($value);
		
		$attributes = array(
			'name'            => esc_attr($settings['param_name']),
			'class'           => esc_attr("wpb_vc_param_value wpb-textinput om-cc-code {$settings['param_name']} {$settings['type']}"),
			'data-om-cc-code' => '',
			'type'            => 'hidden',
			'value'           => esc_attr($value),
		);
		
		if (isset($settings['placeholder'])) {
			$attributes['placeholder'] = esc_attr($settings['placeholder']);
		}
		
		return HtmlWriter::init()->input($attributes, true);
	}
	
	/**
	 * @param array $settings
	 * @param mixed $value
	 *
	 * @return string
	 */
	public function __description(array $settings, $value) {
		return '';
	}
	
	/**
	 * @param array $settings
	 * @param mixed $value
	 *
	 * @return string
	 */
	public function __inputs(array $settings, $value) {
		$value  = htmlspecialchars($value);
		$values = empty($value) ? array() : explode('|||', $value);
		$count  = isset($settings['count']) && is_numeric($settings['count']) ? $settings['count'] : 2;
		$width  = 100 / $count;
		
		$attributes = array(
			'name'  => esc_attr($settings['param_name']),
			'class' => esc_attr("wpb_vc_param_value wpb-textinput {$settings['param_name']} {$settings['type']}"),
			'type'  => 'hidden',
			'value' => esc_attr($value),
		);
		
		$html = HtmlWriter::init()
		                  ->input($attributes, true)
		                  ->div('class="vc_row"');
		
		$attributes['type']              = 'text';
		$attributes['data-om-cc-inputs'] = '';
		
		if (!empty($settings['placeholder']) && is_string($settings['placeholder'])) {
			$attributes['placeholder'] = esc_attr($settings['placeholder']);
		}
		
		for ($index = 0; $index < $count; $index++) {
			$attributes['name']  = esc_attr($settings['param_name']) . "[{$index}]";
			$attributes['value'] = isset($values[$index]) ? esc_attr(preg_replace('/[^\d]/', '', $values[$index])) : '';
			
			if (is_array($settings['placeholder']) && isset($settings['placeholder'][$index])) {
				$attributes['placeholder'] = esc_attr($settings['placeholder'][$index]);
			}
			$html->div(array('class' => 'vc_col-sm-12', 'style' => "width:{$width}%"))
			     ->input($attributes, true)
			     ->end();
		}
		
		return $html;
	}
	
	/**
	 * @param array $settings
	 * @param mixed $value
	 *
	 * @return string
	 */
	public function __mapAddress(array $settings, $value) {
		$value = htmlspecialchars($value);
		
		$values = empty($value) ? array('', '') : explode('|', $value, 2);
		
		$attributes = array(
			'name'  => esc_attr($settings['param_name']),
			'class' => esc_attr("wpb_vc_param_value wpb-textinput {$settings['param_name']}"),
			'type'  => 'hidden',
			'value' => esc_attr($value),
		);
		
		$text = array(
			'name'                   => esc_attr("{$settings['param_name']}-address"),
			'class'                  => esc_attr("wpb_vc_param_value wpb-textinput {$settings['param_name']} {$settings['type']}"),
			'value'                  => esc_attr($values[1]),
			'data-om-cc-map-address' => esc_attr($values[0])
		);
		
		return HtmlWriter::init()
		                 ->input($attributes, true)
		                 ->input($text, true);
	}
	
	/**
	 * @param array $settings
	 * @param mixed $value
	 *
	 * @return string
	 */
	public function __number(array $settings, $value) {
		$value = htmlspecialchars($value);
		
		$attributes = array(
			'name'  => esc_attr($settings['param_name']),
			'class' => esc_attr("wpb_vc_param_value wpb-textinput {$settings['param_name']} {$settings['type']}"),
			'type'  => 'number',
			'value' => esc_attr($value),
		);
		
		if (isset($settings['min'])) {
			$attributes['min'] = esc_attr($settings['min']);
		}
		
		if (isset($settings['max'])) {
			$attributes['max'] = esc_attr($settings['max']);
		}
		
		if (!empty($settings['step'])) {
			$attributes['step'] = esc_attr($settings['step']);
		}
		
		if (isset($settings['placeholder'])) {
			$attributes['placeholder'] = esc_attr($settings['placeholder']);
		}
		
		return HtmlWriter::init()->input($attributes, true);
	}
	
	/**
	 * @param array $settings
	 * @param mixed $value
	 *
	 * @return string
	 */
	public function __postTypes(array $settings, $value) {
		$dataAttribute = 'data-' . str_replace('_', '-', $settings['type']);
		$attributes    = array(
			'name'                => esc_attr($settings['param_name']),
			'class'               => esc_attr("wpb_vc_param_value wpb-textinput {$settings['param_name']} {$settings['type']}"),
			'type'                => 'hidden',
			'value'               => $value,
			$dataAttribute        => 'select[name="' . $settings['bind'] . '"]',
			'data-om-cc-api-host' => get_home_url(),
		);
		
		if ($settings['type'] === 'om_cc_posttype_postfields') {
			$attributes['data-om-cc-title-source-meta'] = Options::get('portfolio_title_source_meta_usage');
		}
		
		if ($settings['type'] === 'om_cc_posttype_terms') {
			$attributes['data-om-cc-empty']       = esc_html__('There are no taxonomies within this post type', OM_CP_SHARED_TEXTDOMAIN);
			$attributes['data-om-cc-placeholder'] = esc_html__('Choose terms...', OM_CP_SHARED_TEXTDOMAIN);
		}
		
		if ($settings['type'] === 'om_cc_post_select') {
			$attributes['data-om-cc-placeholder'] = esc_html__('Choose posts...', OM_CP_SHARED_TEXTDOMAIN);
		}
		
		return HtmlWriter::init()->input($attributes, true);
	}
	
	/**
	 * @param array $settings
	 * @param mixed $value
	 *
	 * @return string
	 */
	public function __units(array $settings, $value) {
		$value = htmlspecialchars($value);
		
		$attributes = array(
			'name'       => esc_attr($settings['param_name']),
			'class'      => esc_attr("wpb_vc_param_value wpb-textinput {$settings['param_name']} {$settings['type']}"),
			'type'       => 'units',
			'value'      => esc_attr($value),
			'data-units' => json_encode($settings['units']),
		);
		
		if (isset($settings['min'])) {
			$attributes['min'] = esc_attr($settings['min']);
		}
		
		if (isset($settings['max'])) {
			$attributes['max'] = esc_attr($settings['max']);
		}
		
		if (!empty($settings['step'])) {
			$attributes['step'] = esc_attr($settings['step']);
		}
		
		if (isset($settings['placeholder'])) {
			$attributes['placeholder'] = esc_attr($settings['placeholder']);
		}
		
		return HtmlWriter::init()->input($attributes, true);
	}
	
	/**
	 * @param array $settings
	 * @param mixed $value
	 *
	 * @return string
	 */
	public function __colorAdvanced(array $settings, $value) {
		$attributes = array(
			'id'                        => esc_attr($settings['param_name']),
			'name'                      => esc_attr($settings['param_name']),
			'type'                      => 'text',
			'value'                     => esc_attr($value),
			'class'                     => 'pluto-color-control',
			'data-om-cc-color-advanced' => 'true'
		);
		
		return HtmlWriter::init()->input($attributes, true);
	}
	
	/**
	 * @return int
	 */
	
	private static function _getStaticId() {
		static $id;
		if (!$id) {
			$id = 0;
		}
		$id++;
		
		return $id;
	}
	
	/**
	 * @param string $group
	 * @param int    $weight
	 *
	 * @return array
	 */
	public static function clearfix($group, $weight = 0) {
		return array(
			'group'            => $group,
			'param_name'       => '_cc_clearfix_' . self::_getStaticId(),
			'type'             => 'clearfix',
			'edit_field_class' => 'vc_clearfix',
			'weight'           => $weight
		);
	}
	
	/**
	 * @param string $group
	 * @param string $content
	 * @param int    $weight
	 *
	 * @return array
	 */
	public static function title($group, $content, $weight = 0) {
		return array(
			'group'            => $group,
			'param_name'       => '_cc_title_' . self::_getStaticId(),
			'type'             => 'om_cc_description',
			'edit_field_class' => 'vc_column vc_col-xs-12 cc-shortcode-param-title',
			'description'      => $content,
			'weight'           => $weight
		);
	}
	
	/**
	 * @param string $group
	 * @param int    $weight
	 *
	 * @return array
	 */
	public static function hr($group, $weight = 0) {
		return array(
			'group'            => $group,
			'param_name'       => '_cc_hr_' . self::_getStaticId(),
			'type'             => 'om_cc_description',
			'edit_field_class' => 'vc_column vc_col-xs-12 cc-shortcode-param-hr',
			'description'      => '<hr>',
			'weight'           => $weight
		);
	}
}
