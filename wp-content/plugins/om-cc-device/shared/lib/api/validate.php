<?php

namespace OM\CC\Shared\Lib\Api;

class Validate {
	
	/**
	 * Validate post type name
	 *
	 * @param string           $param
	 * @param \WP_REST_Request $request
	 * @param string           $key
	 *
	 * @return string|\WP_Error
	 */
	public static function postTypeName($param, $request, $key) {
		if (!post_type_exists($param)) {
			return new \WP_Error('broke', __('Post type not exists', OM_CP_SHARED_TEXTDOMAIN));
		}
		
		return $param;
	}
	
	public static function isBool($param) {
		return $param === 'true' || $param === 'false';
	}
}