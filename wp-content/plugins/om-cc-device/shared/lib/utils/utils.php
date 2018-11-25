<?php

namespace OM\CC\Shared\Lib\Utils;

class Utils {
	
	/**
	 * Check if Visual Composer is activated
	 * @return bool
	 */
	public static function isVisualComposer() {
		return defined('WPB_VC_VERSION');
	}
	
	/**
	 * Escaping for styles content attributes.
	 *
	 * @param string $css
	 *
	 * @return string
	 */
	public static function escStyles($css) {
		return str_replace('<', '', $css);
	}
	
	/**
	 * Convert attributes list to string
	 *
	 * @param array $attributes List of attributes, keys is attributes' names
	 *
	 * @return string attributes as string
	 */
	public static function getAttributesString($attributes) {
		if (!is_array($attributes)) {
			return '';
		}
		
		$strings = array();
		
		foreach ($attributes as $name => $value) {
			if ($name === 'class' && is_array($value)) {
				$value = implode(' ', $value);
			}
			
			$strings[] = esc_attr($name) . '="' . esc_attr($value) . '"';
		}
		
		return implode(' ', $strings);
	}
	
	/**
	 * @param array  $array
	 * @param string $key
	 * @param mixed  $default
	 *
	 * @return mixed array value or default, if key not specified
	 */
	public static function getArrayValue(array $array, $key, $default = null) {
		return array_key_exists($key, $array) ? $array[$key] : $default;
	}
	
	/**
	 * @param array $options
	 *
	 * @return array
	 */
	public static function makeVCDropdownItems(array $options) {
		$items = array();
		
		foreach ($options as $option) {
			$items[$option['text']] = $option['value'];
		}
		
		return $items;
	}
	
	/**
	 * @param string|null $postType
	 *
	 * @return \stdClass[]
	 */
	public static function getTaxonomiesByPostType($postType = null) {
		/** @var \stdClass[] $taxonomies */
		$taxonomies = get_taxonomies(array(), 'objects');
		
		if ($postType !== null) {
			$taxonomies = array_filter($taxonomies, function ($taxonomy) use ($postType) {
				return in_array($postType, $taxonomy->object_type, true);
			});
		}
		
		return $taxonomies;
	}
	
	/**
	 * @param string $postType
	 *
	 * @return string[]
	 */
	public static function getMetaKeysByPostType($postType) {
		/** @var \wpdb $wpdb */
		global $wpdb;
		
		$request = sprintf(
			'SELECT DISTINCT %2$s.meta_key FROM %1$s LEFT JOIN %2$s ON %1$s.ID = %2$s.post_id WHERE %2$s.meta_key NOT REGEXP "^_" AND %2$s.meta_value NOT REGEXP "(a:[0-9]:{)|(^(0|1)$)" AND %1$s.post_type = "%3$s"',
			(string)$wpdb->posts,
			(string)$wpdb->postmeta,
			(string)$postType
		);
		
		return array_map(function ($item) {
			return (string)$item['meta_key'];
		}, (array)$wpdb->get_results($request, ARRAY_A));
	}
}