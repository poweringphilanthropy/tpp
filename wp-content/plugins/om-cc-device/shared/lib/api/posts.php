<?php

namespace OM\CC\Shared\Lib\Api;

use OM\CC\Shared\Lib\Utils\Utils;

class Posts {
	
	/**
	 * @param \WP_REST_Request $request
	 *
	 * @return \array[]
	 */
	public static function get(\WP_REST_Request $request) {
		/** @var string $postType */
		$postType = $request->get_param('type');
		
		/** @var array[] $posts */
		$posts = array_map(function ($post) {
			return array('id' => $post->ID, 'title' => $post->post_title);
		}, get_posts(array(
			'posts_per_page' => -1,
			'post_type'      => $postType
		)));
		
		return $posts;
	}
	
	public static function getPostFields(\WP_REST_Request $request) {
		/** @var string $postType */
		$postType = $request->get_param('type');
		$isMetaKeys = $request->get_param('ismetakeys');
		
		$postKeys = array(
			array('key' => 'post_title', 'title' => esc_html__('Title')),
			array('key' => 'post_excerpt', 'title' => esc_html__('Excerpt')),
			array('key' => 'post_author', 'title' => esc_html__('Author')),
			array('key' => 'post_date', 'title' => esc_html__('Date')),
		);
		
		$taxonomies = Utils::getTaxonomiesByPostType($postType);
		
		foreach ($taxonomies as $taxonomy) {
			$postKeys[] = array(
				'key'   => 'tax__' . $taxonomy->name,
				'title' => $taxonomy->labels->name
			);
		}
		
		if ($isMetaKeys === 'true') {
			$metaKeys = $postType !== null ? Utils::getMetaKeysByPostType($postType) : array();
			
			$search  = array('colors-creative_', 'om_cc_', '_current', '_');
			$replace = array('', '', '', ' ');
			
			foreach ($metaKeys as $key) {
				$postKeys[] = array(
					'key'   => $key,
					'title' => ucfirst(str_replace($search, $replace, $key))
				);
			}
		}		
		
		return $postKeys;
	}
}
