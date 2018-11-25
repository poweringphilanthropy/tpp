<?php

namespace OM\CC\Shared\Lib\System;

abstract class Singleton {
	
	/**
	 * @var Singleton[] -- unique instances for each successor
	 */
	private static $_instances = array();
	
	/**
	 * Singleton constructor.
	 */
	protected function __construct() {
	}
	
	/**
	 * Get singleton unique instance.
	 * @return static -- instance
	 */
	final public static function getInstance() {
		$class = get_called_class();
		
		if (!array_key_exists($class, self::$_instances)) {
			self::$_instances[$class] = new $class();
		}
		
		return self::$_instances[$class];
	}
	
	/**
	 * Magic cloning.
	 */
	final protected function __clone() {
		throw new \LogicException('Cloning not allowed');
	}
	
	/**
	 * Serialization.
	 * @return string|NULL -- serialized data
	 */
	final public function serialize() {
		throw new \LogicException('Serialization not allowed');
	}
	
	/**
	 * Deserialization.
	 *
	 * @param string|NULL $serialized -- serialized data
	 */
	final public function unserialize($serialized) {
		throw new \LogicException('Deserialization not allowed');
	}
}