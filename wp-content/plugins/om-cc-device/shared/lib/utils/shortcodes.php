<?php

namespace OM\CC\Shared\Lib\Utils;

class Shortcodes {
	
	/**
	 * @var array Stores posts shortcodes
	 */
	private static $_postsShortcodes = array();
	
	/**
	 * Retrieve list of all shortcodes from post content
	 *
	 * @param integer $postId
	 * @param string  $tag - tag name
	 *
	 * @return array List of shortcodes with attributes
	 */
	public static function parseContent($postId, $tag) {
		if (!array_key_exists($postId, self::$_postsShortcodes)) {
			$post       = get_post($postId);
			$shortcodes = array();
			
			if ($post && preg_match_all('/\[[\w]+[^\]]+/', $post->post_content, $strings)) {
				
				foreach ((array)$strings[0] as $string) {
					$data = explode(' ', $string, 2);
					
					$shortcodes[] = array(
						'tag'        => str_replace('[', '', $data[0]),
						'attributes' => array_key_exists(1, $data) ? shortcode_parse_atts($data[1]) : array()
					);
				}
			}
			
			self::$_postsShortcodes[$postId] = $shortcodes;
		}
		
		return array_filter(self::$_postsShortcodes[$postId], function ($shortcode) use ($tag) {
			return $shortcode['tag'] === $tag;
		});
	}
	
	/**
	 * Get unique shortcode hash using attributes
	 *
	 * @param string $tag      Tag name
	 * @param array  $settings List of parameters to calculate hash
	 *
	 * @return string Hash
	 */
	public static function hash($tag, $settings) {
		return $tag . '_' . hash('crc32', serialize($settings));
	}
}