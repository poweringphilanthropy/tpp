<?php

namespace OM\CC\Shared\Lib\Html;

class CssWriter {
	
	/**
	 * @var array List of selectors with parameters
	 */
	private $_selectors = array();
	
	/**
	 * Create new instance of Css class
	 * @return $this
	 */
	public static function init() {
		return new CssWriter();
	}
	
	/**
	 * Add parameter to selector
	 *
	 * @param string|array $selector  selector name or list of names
	 * @param string|array $parameter parameter name or list of parameters values with parameters names as keys
	 * @param string       $value     if $parameter is string, than set as value
	 *
	 * @return $this
	 */
	public function set($selector, $parameter, $value = null) {
		$selectors  = is_string($selector) ? array($selector) : (array)$selector;
		$parameters = is_string($parameter) ? array($parameter => $value) : (array)$parameter;
		
		foreach ($selectors as $name) {
			if (array_key_exists($name, $this->_selectors)) {
				$this->_selectors[$name] = array_merge($this->_selectors[$name], $parameters);
			} else {
				$this->_selectors[$name] = $parameters;
			}
		}
		
		return $this;
	}
	
	/**
	 * Clear all parameters
	 * @return $this
	 */
	public function clear() {
		$this->_selectors = array();
		
		return $this;
	}
	
	/**
	 * Return finalized CSS string
	 * @return string
	 */
	public function __toString() {
		$css = '';
		
		foreach ($this->_selectors as $selector => $parameters) {
			$params = null;
			
			foreach ((array)$parameters as $pKey => $pVal) {
				if (null !== $pVal && '' !== $pVal) {
					$params .= $pKey . ': ' . $pVal . ';';
				}
			}
			
			if (null !== $params) {
				$css .= "{$selector} {{$params}}";
			}
		}
		
		return $css;
	}
}
