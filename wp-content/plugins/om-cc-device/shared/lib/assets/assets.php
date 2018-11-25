<?php

namespace OM\CC\Shared\Lib\Assets;

use OM\CC\Shared\Lib\System\Singleton;

class Assets extends Singleton {
	
	private static $DEFAULTS = array(
		'version'      => true,
		'dependencies' => array(),
		'footer'       => true,
		'scope'        => 'wp',
		'override'     => false
	);
	
	private static $CDN_TEMPLATE = '<script src="%s"></script><script>%s||document.write(\'<script src="%s"><\/script>\')</script>';
	
	/**
	 * @var boolean
	 */
	private $_isAdmin;
	
	/**
	 * @var array[] List of registered styles
	 */
	private $_styles = array();
	
	/**
	 * @var array[] List of registered scripts
	 */
	private $_scripts = array();
	
	/**
	 * @var string[] List of enqueued styles
	 */
	private $_enqueuedStyles = array();
	
	/**
	 * @var string[] List of enqueued scripts
	 */
	private $_enqueuedScripts = array();
	
	/**
	 * @var array[] List of enqueued scripts with CDN links
	 */
	private $_cdn = array();
	
	/**
	 * Assets constructor.
	 */
	protected function __construct() {
		parent::__construct();
		
		$this->_isAdmin = is_admin();
		$hook           = $this->_isAdmin ? 'admin' : 'wp';
		
		add_action('init', array($this, '__register'));
		add_action("{$hook}_enqueue_scripts", array($this, '__enqueue'));
	}
	
	/**
	 * @param array $styles
	 *
	 * @return $this
	 */
	public function registerStyles(array $styles) {
		$this->_registerAssets($this->_styles, $styles);
		
		return $this;
	}
	
	/**
	 * @param array $scripts
	 *
	 * @return $this
	 */
	public function registerScripts(array $scripts) {
		$this->_registerAssets($this->_scripts, $scripts);
		
		return $this;
	}
	
	/**
	 * @param array $handles
	 *
	 * @return $this
	 */
	public function enqueueStyles(array $handles) {
		$this->_enqueueAssets($this->_enqueuedStyles, $handles, $this->_styles);
		
		return $this;
	}
	
	/**
	 * @param array $handles
	 *
	 * @return $this
	 */
	public function enqueueScripts(array $handles) {
		$this->_enqueueAssets($this->_enqueuedScripts, $handles, $this->_scripts);
		
		return $this;
	}
	
	/**
	 * Register assets on action
	 */
	public function __register() {
		foreach ($this->_styles as $style) {
			if ($style['override']) {
				wp_deregister_style($style['handle']);
			}
			
			wp_register_style(
				$style['handle'],
				$style['source'],
				$style['dependencies'],
				$style['version']
			);
		}
		
		foreach ($this->_scripts as $script) {
			if ($script['override']) {
				wp_deregister_script($script['handle']);
			}
			
			wp_register_script(
				$script['handle'],
				$script['source'],
				$script['dependencies'],
				$script['version'],
				$script['footer']
			);
		}
	}
	
	/**
	 * Enqueue assets on action
	 */
	public function __enqueue() {
		
		foreach ($this->_enqueuedStyles as $handle) {
			wp_enqueue_style($handle);
		}
		
		foreach ($this->_enqueuedScripts as $handle) {
			wp_enqueue_script($handle);
			
			$script = $this->_scripts[$handle];
			if (isset($script['cdn'], $script['test'])) {
				$this->_cdn[$script['handle']] = $script;
			}
		}
		
		if (count($this->_cdn)) {
			add_filter('script_loader_src', array($this, '__cdn'), 10, 2);
			add_action('wp_head', array($this, '__cdn'), 10, 2);
		}
	}
	
	/**
	 * Replace local script with CDN and local fallback
	 *
	 * @param string $src    string local script link
	 * @param string $handle script name
	 *
	 * @return string|null Null if process CDN script, otherwise do nothing and return original src
	 */
	public function __cdn($src, $handle) {
		if (array_key_exists($handle, $this->_cdn)) {
			$script = $this->_cdn[$handle];
			
			printf(self::$CDN_TEMPLATE, $script['cdn'], $script['test'], $src);
			
			return null;
		}
		
		return $src;
	}
	
	/**
	 * @param array[] $_assets
	 * @param array[] $assets
	 */
	private function _registerAssets(&$_assets, array $assets) {
		foreach ($assets as $asset) {
			if (!array_key_exists('handle', $asset)) {
				throw new \LogicException('Asset`s handle is not defined');
			}
			
			if (!array_key_exists('source', $asset)) {
				throw new \LogicException('Asset`s source is not defined');
			}
			
			$_assets[$asset['handle']] = array_merge(self::$DEFAULTS, $asset);
		}
	}
	
	/**
	 * @param string[] $_enqueued
	 * @param string[] $handles
	 * @param array[]  $_assets
	 */
	private function _enqueueAssets(&$_enqueued, $handles, $_assets) {
		$scope = array('both', $this->_isAdmin ? 'admin' : 'wp');
		
		$filtered = array_filter($handles, function ($handle) use ($_assets, $scope) {
			return in_array($_assets[$handle]['scope'], $scope, true);
		});
		
		$_enqueued = array_unique(array_merge($_enqueued, $filtered));
	}
}
