<?php

namespace OM\CC\Shared\Lib\Assets;

abstract class Definitions {
	
	/**
	 * @var array[]
	 */
	protected static $_common = array();
	
	/**
	 * @var array[]
	 */
	protected static $_public = array();
	
	/**
	 * @var array[]
	 */
	protected static $_admin = array();
	
	/**
	 * @param string $urlBase
	 * @param string $defaultVersion
	 *
	 * @return array[]
	 */
	public static function all($urlBase, $defaultVersion) {
		
		$a = array_merge(
			self::_prepare(static::$_common, 'both', $urlBase, $defaultVersion),
			self::_prepare(static::$_public, 'wp', $urlBase, $defaultVersion),
			self::_prepare(static::$_admin, 'admin', $urlBase, $defaultVersion)
		);
		
		return $a;
	}
	
	private static function _prepare($assets, $scope, $urlBase, $defaultVersion) {
		return array_map(function ($asset) use ($scope, $urlBase, $defaultVersion) {
			$asset['scope']  = $scope;
			$asset['source'] = $urlBase . $asset['source'];
			
			if (!array_key_exists('version', $asset)) {
				$asset['version'] = $defaultVersion;
			}
			
			return $asset;
		}, $assets);
	}
}