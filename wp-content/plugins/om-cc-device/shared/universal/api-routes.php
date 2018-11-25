<?php

namespace OM\CC\Shared\Universal;

use OM\CC\Shared\Lib\Api\Routes;

class ApiRoutes extends UniversalUnitary {
	
	protected static function _initOnce() {
		
		$namespace = 'OM\CC\Shared\Lib\Api\\';
		
		$routes = array(
			'/posts/'           => array(
				'methods'  => 'GET',
				'callback' => "{$namespace}Posts::get",
				'args'     => array(
					'type' => array(
						'validate_callback' => "{$namespace}Validate::postTypeName"
					),
				),
			),
			'/posts/post-fields/' => array(
				'methods'  => 'GET',
				'callback' => "{$namespace}Posts::getPostFields",
				'args'     => array(
					'type' => array(
						'validate_callback' => "{$namespace}Validate::postTypeName"
					),
					'ismetakeys' => array(
						'validate_callback' => "{$namespace}Validate::isBool"
					),
				),
			),
			'/taxonomies/'      => array(
				'methods'  => 'GET',
				'callback' => "{$namespace}Taxonomies::get",
				'args'     => array(
					'type' => array(
						'validate_callback' => "{$namespace}Validate::postTypeName"
					),
				),
			)
		);
		
		Routes::register($routes);
	}
	
}