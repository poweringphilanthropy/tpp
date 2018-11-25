<?php

namespace OM\CC\Shared\Lib\Data;

use OM\CC\Shared\Lib\Utils\Utils;

class BaseEntity {
	
	/**
	 * @var string Entity name
	 */
	protected $name;
	
	/**
	 * @var string Singular name for UI
	 */
	protected $singular;
	
	/**
	 * @var string Plural name for UI
	 */
	protected $plural;
	
	/**
	 * @var array Settings for registering
	 */
	protected $settings;
	
	/**
	 * Base entity constructor
	 *
	 * @param array $options Custom entity options
	 */
	protected function __construct(array $options) {
		if (!array_key_exists('name', $options) || !is_string($options['name'] || $options['name'] === '')) {
			throw new \InvalidArgumentException('The entity name must be specified');
		}
		
		$this->name     = $options['name'];
		$this->singular = Utils::getArrayValue($options, 'singular', ucwords(str_replace('_', ' ', $this->name)));
		$this->plural   = Utils::getArrayValue($options, 'plural', $this->singular);
	}
	
	/**
	 * Compose common settings for register
	 *
	 * @param array $defaults Default settings
	 * @param array $options  Custom entity options
	 * @param array $labels   An array of labels for this entity
	 *
	 * @return array
	 */
	protected function _parseSettings($defaults, $options, $labels) {
		$settings = array_merge($defaults, Utils::getArrayValue($options, 'args', array()));
		
		// Labels
		$settings['label'] = Utils::getArrayValue($settings, 'label', $this->plural);
		
		if (array_key_exists('labels', $options)) {
			$labels = array_merge($labels, $options['labels']);
		}
		
		foreach ($labels as &$label) {
			$label = sprintf($label, $this->singular, $this->plural);
		}
		
		$settings['labels'] = $labels;
		
		$settings['description'] = Utils::getArrayValue($options, 'description', null);
		
		// Rewrite
		if (array_key_exists('rewrite', $settings) && has_action('after_switch_theme', 'flush_rewrite_rules') !== 10) {
			add_action('after_switch_theme', 'flush_rewrite_rules');
		}
		
		return $settings;
	}
}