<?php

if (!function_exists('om_cc_autoload')) {
	/**
	 * @param string $version
	 * @param string $pluginPath
	 * @param string $pluginUrl
	 * @param string $pluginNamespace
	 */
	function om_cc_autoload($version, $pluginPath, $pluginUrl, $pluginNamespace) {
		global $om_cc_sharedVersion,
		       $om_cc_sharedPath,
		       $om_cc_sharedUrl;
		
		if (null === $om_cc_sharedVersion || version_compare($om_cc_sharedVersion, $version, '<')) {
			$om_cc_sharedVersion = $version;
			$om_cc_sharedPath    = $pluginPath . 'shared';
			$om_cc_sharedUrl     = $pluginUrl . 'shared/';
		}
		
		if ($pluginNamespace) {
			_om_cc_registerAutoload($pluginNamespace, $pluginPath . 'inc');
		}
	}
}

add_action('plugins_loaded', function () {
	global $om_cc_sharedPath;
	static $initialized;
	
	if (!$initialized && $om_cc_sharedPath) {
		$initialized = true;
		_om_cc_registerAutoload('OM\CC\Shared', $om_cc_sharedPath);
	}
}, 0);

if (!function_exists('_om_cc_registerAutoload')) {
	/**
	 * @param string $namespace -- Global namespace
	 * @param string $path      -- Global namespace root path
	 */
	function _om_cc_registerAutoload($namespace, $path) {
		spl_autoload_register(function ($class) use ($namespace, $path) {
			if (strpos($class, $namespace) === 0) {
				$in   = array($namespace, '\\', '_',);
				$out  = array('', '/', '-');
				$file = $path . strtolower(preg_replace('/(\w)([A-Z])/', '$1-$2', str_replace($in, $out, $class))) . '.php';
				
				if (file_exists($file)) {
					require_once $file;
				}
			}
		});
	}
}

if (!function_exists('om_cc_makeVCContainer')) {
	/**
	 * @param string $shortcodeTag --
	 */
	function om_cc_makeVCContainer($shortcodeTag) {
		if (class_exists('WPBakeryShortCodesContainer')) {
			eval("class WPBakeryShortCode_$shortcodeTag extends WPBakeryShortCodesContainer {}");
		}
	}
}
