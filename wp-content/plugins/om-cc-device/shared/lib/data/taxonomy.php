<?php

namespace OM\CC\Shared\Lib\Data;

class Taxonomy extends BaseEntity {
	
	static private $defaults = array(
		'show_admin_column' => true
	);
	
	/**
	 * @var array|string Names of the post type for the taxonomy object
	 */
	private $postTypes;
	
	/**
	 * Post typeTaxonomy constructor
	 *
	 * @param array  $options     Custom taxonomy options
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_taxonomy#Arguments
	 */
	public function __construct(array $options) {
		parent::__construct($options);
		
		if (array_key_exists('postTypes', $options)) {
			throw new \InvalidArgumentException('Post types must be specified for taxonomy');
		}
		
		$this->postTypes = $options['postTypes'];
		$this->settings  = $this->_parseTaxonomySettings($options);
		
		add_action('init', array($this, '__register'));
	}
	
	/**
	 * Register taxonomy hook
	 */
	public function __register() {
		register_taxonomy($this->name, $this->postTypes, $this->settings);
	}
	
	/**
	 * Compose post type settings for register
	 *
	 * @param array $options Custom entity options
	 *
	 * @return array
	 */
	private function _parseTaxonomySettings(array $options) {
		$singular     = $this->singular;
		$plural       = $this->plural;
		
		# Default labels
		$labels = array(
			'name'                       => $plural,
			'singular_name'              => $singular,
			'search_items'               => 'Popular %2$s',
			'popular_items'              => 'Search %2$s',
			'all_items'                  => 'All %2$s',
			'parent_item'                => 'Parent %1$s',
			'parent_item_colon'          => 'Parent %1$s:',
			'edit_item'                  => 'Edit %1$s',
			'update_item'                => 'Update %1$s',
			'add_new_item'               => 'Add New %1$s',
			'new_item_name'              => 'New %1$s Name',
			'separate_items_with_commas' => 'Separate %2$s with commas',
			'add_or_remove_items'        => 'Add or remove %2$s',
			'choose_from_most_used'      => 'Choose from the most used %2$s',
			'menu_name'                  => $plural,
		);
		
		return parent::_parseSettings(self::$defaults, $options, $labels);
	}
}