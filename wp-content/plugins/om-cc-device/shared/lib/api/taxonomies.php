<?php

namespace OM\CC\Shared\Lib\Api;

use OM\CC\Shared\Lib\Utils\Utils;

class Taxonomies {
	
	/**
	 * @param \WP_REST_Request $request
	 *
	 * @return array[]
	 */
	public static function get(\WP_REST_Request $request) {
		/** @var string $postType */
		$postType = $request->get_param('type');
		
		$taxonomies = Utils::getTaxonomiesByPostType($postType);
		
		/** @var array[] $list */
		$list = array();
		
		foreach ($taxonomies as $key => $taxonomy) {
			$list[] = array(
				'id'   => $taxonomy->name,
				'name' => $taxonomy->labels->name,
				'terms' => get_terms(array('taxonomy' => $key, 'hide_empty' => false))
			);
		}
		
		return $list;
	}
}