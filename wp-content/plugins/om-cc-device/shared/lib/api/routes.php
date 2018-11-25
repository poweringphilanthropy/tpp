<?php

namespace OM\CC\Shared\Lib\Api;

class Routes {
	
	/**
	 * @param array[] $routes
	 */
	public static function register(array $routes) {
		add_action('rest_api_init', function () use ($routes) {
			foreach ($routes as $path => $route) {
				register_rest_route('om-cc', $path, $route);
			}
		});
	}
}
