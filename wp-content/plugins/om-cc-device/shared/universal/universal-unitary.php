<?php

namespace OM\CC\Shared\Universal;

use OM\CC\Shared\Lib\System\Unitary;

abstract class UniversalUnitary extends Unitary {
	
	/**
	 * @var string
	 */
	protected static $_sharedPath;
	
	/**
	 * @var string
	 */
	protected static $_sharedUrl;
	
	/**
	 * @var string
	 */
	protected static $_sharedVersion;
	
	/**
	 * Initialize.
	 */
	public static function init() {
		if (!self::$_sharedVersion) {
			global $om_cc_sharedPath,
			       $om_cc_sharedVersion,
			       $om_cc_sharedUrl;
			
			self::$_sharedPath    = $om_cc_sharedPath;
			self::$_sharedUrl     = $om_cc_sharedUrl;
			self::$_sharedVersion = $om_cc_sharedVersion;
		}
		
		parent::init();
	}
}