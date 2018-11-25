<?php

namespace OM\CC\Shared\Lib\System;

abstract class Unitary {
	
	/**
	 * @var array
	 */
	private static $_initiated = array();
	
	/**
	 * Initialize.
	 */
	public static function init() {
		$class = get_called_class();
		
		if (!array_key_exists($class, self::$_initiated)) {
			self::$_initiated[$class] = true;
			static::_initOnce();
		}
		
		static::_initEver();
	}
	
	/**
	 * Method, that runs only on first initialize.
	 * Override if necessary.
	 */
	protected static function _initOnce() {
	}
	
	/**
	 * Method, that runs on every initialize.
	 * Override if necessary.
	 */
	protected static function _initEver() {
	}
}