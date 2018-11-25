<?php

namespace OM\CC\Shared\Lib\Html;

class Images {
	
	/**
	 * Retrieves image info for given image sizes
	 *
	 * @param int   $id Image attachment ID.
	 * @param array $sizes
	 *
	 * @return array
	 */
	public static function getInfo($id, array $sizes = null) {
		$info = array(
			'alt' => self::getAlt($id)
		);
		
		$thumbnails = self::getThumbnails($id, $sizes);
		$infoSizes  = array();
		
		foreach ($thumbnails as $key => $thumbnail) {
			$infoSizes[is_string($thumbnail['size']) ? $thumbnail['size'] : $key] = $thumbnail;
		}
		
		if (!array_key_exists('full', $infoSizes) && count($infoSizes) && (!$sizes || in_array('full', $sizes, true))) {
			$infoSizes['full'] = end($infoSizes);
		}
		
		$info['sizes'] = $infoSizes;
		
		return $info;
	}
	
	/**
	 * Get responsive image
	 *
	 * @param int   $id
	 * @param array $sizes
	 *
	 * @return array
	 */
	public static function getImageAttributes($id, array $sizes = array()) {
		$attributes = array();
		
		$mime = get_post_mime_type($id);
		
		if ($mime === 'image/gif') {
			$sizes = array('full');
		}
		
		$attributes['class']      = 'lazyload';
		$attributes['data-sizes'] = 'auto';
		
		$info = self::getInfo($id, $sizes);
		
		$attributes['data-srcset'] = implode(',', array_map(function ($size) {
			return esc_url($size['url']) . ' ' . esc_attr($size['width']) . 'w';
		}, $info['sizes']));
		
		$firstSize = reset($info['sizes']);
		
		$attributes['src'] = esc_url($firstSize['url']);
		$attributes['alt'] = esc_attr($info['alt']);
		
		return $attributes;
	}
	
	/**
	 * @param int $id
	 *
	 * @return bool
	 */
	public static function exists($id) {
		return wp_attachment_is_image($id);
	}
	
	/**
	 * @param int    $id
	 * @param string $size
	 *
	 * @return string
	 */
	public static function url($id, $size = 'thumbnail') {
		$url = wp_get_attachment_image_url($id, $size);
		
		return $url ?: '';
	}
	
	/**
	 * @param int   $id
	 * @param array $attributes
	 *
	 * @return string
	 */
	public static function getImageHtml($id, array $attributes = array()) {
		if (self::exists($id)) {
			$sizes = get_post_mime_type($id) === 'image/gif' ? array('full') : null;
			
			$thumbnails = self::getThumbnails($id, $sizes);
			
			$first = reset($thumbnails);
			$last  = end($thumbnails);
			
			$attributes['src']   = reset($first);
			$attributes['alt']   = self::getAlt($id);
			$attributes['width'] = $last['width'];
			
			$thumbnailsData = array();
			
			foreach ($thumbnails as $key => $thumbnail) {
				$thumbnailsData[] = array($key => $thumbnail['url']);
			}
			
			$attributes['data-reactive-image'] = json_encode($thumbnailsData);
			
			return (string)HtmlWriter::init()->img($attributes, true);
		}
		
		return '';
	}
	
	/**
	 * @param int   $postId
	 * @param array $attributes
	 *
	 * @return string
	 */
	public static function getThumbnailHtml($postId, array $attributes = array()) {
		$id = get_post_thumbnail_id($postId);
		
		return self::getImageHtml($id, $attributes);
	}
	
	/**
	 * Get the list of an image' sizes
	 * @return array
	 */
	public static function getSizesListItems() {
		$outData = array();
		
		foreach (self::_getAdditionalSizes() as $key => $sizes) {
			$outData[self::_sizesListItemName($sizes, strtolower($key))] = $key;
		}
		
		return $outData;
	}
	
	/**
	 * Get image sizes
	 *
	 * @param bool|null $crop
	 *
	 * @return array
	 */
	private static function _getAdditionalSizes($crop = null) {
		global $_wp_additional_image_sizes;
		
		$sizes                        = array();
		$get_intermediate_image_sizes = get_intermediate_image_sizes();
		
		// Create the full array with sizes and crop info
		foreach ($get_intermediate_image_sizes as $_size) {
			if (in_array($_size, array('thumbnail', 'medium', 'large'), null)) {
				$sizes[$_size]['width']  = get_option($_size . '_size_w');
				$sizes[$_size]['height'] = get_option($_size . '_size_h');
				$sizes[$_size]['crop']   = (bool)get_option($_size . '_crop');
			} elseif (array_key_exists($_size, $_wp_additional_image_sizes)) {
				$sizes[$_size] = array(
					'width'  => $_wp_additional_image_sizes[$_size]['width'],
					'height' => $_wp_additional_image_sizes[$_size]['height'],
					'crop'   => $_wp_additional_image_sizes[$_size]['crop']
				);
			}
		}
		
		return $crop !== null
			? array_filter($sizes, function (array $size) use ($crop) {
				return $size['crop'] === $crop;
			})
			: $sizes;
	}
	
	/**
	 * Retrieves image thumbnails info
	 *
	 * @param int   $id
	 * @param array $sizes
	 *
	 * @return array
	 */
	public static function getThumbnails($id, array $sizes = null) {
		static $allSizes;
		
		if (!$sizes) {
			if (!$allSizes) {
				$allSizes   = array_keys(self::_getAdditionalSizes());
				$allSizes[] = 'full';
			}
			
			$sizes = $allSizes;
		}
		
		$data = array();
		
		foreach ($sizes as $size) {
			$info = wp_get_attachment_image_src($id, $size);
			
			if ($info) {
				// Jetpack Photon service removes image size, need to restore it from url:
				if ((!array_key_exists(1, $info) || !array_key_exists(2, $info)) && $info[0]) {
					$location = parse_url($info[0]);
					
					if (array_key_exists('query', $location)) {
						$query = array();
						parse_str($location['query'], $query);
						
						if (array_key_exists('w', $query)) {
							$info[1] = $query['w'];
						}
						
						if (array_key_exists('h', $query)) {
							$info[2] = $query['h'];
						}
					}
				}
				
				$data[] = array(
					'size'   => $size,
					'url'    => $info[0],
					'width'  => $info[1],
					'height' => $info[2]
				);
			}
		}
		
		usort($data, function (array $first, array $second) {
			$key = $first['width'] === $second['width'] ? 'height' : 'width';
			
			return $first[$key] - $second[$key];
		});
		
		$thumbnails = array();
		
		foreach ($data as $item) {
			$thumbnails[$item['width'] . 'x' . $item['height']] = $item;
		}
		
		return $thumbnails;
	}
	
	/**
	 * Get the list of an image size names
	 *
	 * @param array  $size
	 * @param string $name
	 *
	 * @return string
	 */
	private static function _sizesListItemName(array $size, $name) {
		if ($size['crop']) {
			$format = '%1$s - width: %2$d, height: %3$d';
		} else if ($size['height'] === 0) {
			$format = '%1$s - max width: %2$d';
		} else if ($size['width'] >= 9999) {
			$format = '%1$s - max height: %3$d';
		} else {
			$format = '%1$s - max width: %2$d, max height: %3$d';
		}
		
		return sprintf($format, ucwords(str_replace('-', ' ', $name)), $size['width'], $size['height']);
	}
	
	/**
	 * Get image alt
	 *
	 * @param int $id Image attachment ID.
	 *
	 * @return string
	 */
	public static function getAlt($id) {
		$alt = trim(strip_tags(get_post_meta($id, '_wp_attachment_image_alt', true)));
		
		if (!$alt) {
			$attachment = get_post($id);
			$alt        = trim(strip_tags(($attachment->post_excerpt ?: $attachment->post_title)));
		}
		
		return $alt;
	}
}
