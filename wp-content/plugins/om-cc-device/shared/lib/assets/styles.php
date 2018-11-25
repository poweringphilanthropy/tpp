<?php

namespace OM\CC\Shared\Lib\Assets;

class Styles extends Definitions {
	
	// Common
	
	const IONICONS = 'ionicons';
	
	// Public
	
	const OM_ARROWS = 'om_cc_arrows';
	
	const OM_PHOTOSWIPE_LIGHTBOX = 'om_cc_photoswipe_lightbox';
	
	// Admin
	
	const CHOSEN = 'chosen';
	
	const OM_COLOR_ADVANCED = 'om_cc_color_advanced';
	
	const OM_IMAGE_PREVIEW = 'om_cc_image_preview';
	
	const OM_PARAMS = 'om_cc_params';
	
	protected static $_common = array(
		array(
			'handle'  => self::IONICONS,
			'source'  => 'assets/css/vendor/ionicons.min.css',
			'version' => '2.0.1'
		),
	);
	
	protected static $_public = array(
		array(
			'handle' => self::OM_ARROWS,
			'source' => 'assets/css/arrows.min.css'
		),
		array(
			'handle' => self::OM_PHOTOSWIPE_LIGHTBOX,
			'source' => 'assets/css/photoswipe/photoswipe.min.css'
		)
	);
	
	protected static $_admin = array(
		array(
			'handle' => self::CHOSEN,
			'source' => 'assets/css/vendor/chosen.min.css'
		),
		array(
			'handle' => self::OM_COLOR_ADVANCED,
			'source' => 'assets/css/admin/color-advanced.css'
		),
		array(
			'handle' => self::OM_IMAGE_PREVIEW,
			'source' => 'assets/css/admin/image-preview.css'
		),
		array(
			'handle' => self::OM_PARAMS,
			'source' => 'assets/css/admin/params.css'
		),
	);
}