<?php

namespace OM\CC\Shared\Lib\Assets;

class Scripts extends Definitions {
	
	// Public
	
	const JQUERY_REACTIVE_IMAGES = 'jquery_reactive_images';

	const JQUERY_SCROLLMAGIC = 'jquery_scrollmagic';
	
	const LAZYSIZES = 'lazysizes';

	const IMAGES_LOADED = 'images_loaded';

	const MASONRY = 'masonry';

	const MODERNIZR = 'modernizr';

	const PHOTOSWIPE = 'photoswipe';

	const PHOTOSWIPE_UI = 'photoswipe_ui';

	const SCROLLMAGIC = 'scrollmagic';

	const OM_GRID = 'om_cc_grid';

	const OM_LAZYSIZES = 'om_cc_lazysizes';

	const OM_PHOTOSWIPE_LIGHTBOX = 'om_cc_photoswipe_lightbox';

	const OM_SCROLL = 'om_cc_scroll';

	// Admin
	
	const JQUERY_CHOSEN = 'jquery_chosen';
	
	const OM_COLOR_ADVANCED = 'om_cc_color_advanced';
	
	const OM_BREAKPOINT_SELECT = 'om_cc_breakpoint_select';
	
	const OM_DEVICE_SELECT = 'om_cc_device_select';
	
	const OM_IMAGE_PREVIEW = 'om_cc_image_preview';
	
	protected static $_public = array(
		array(
			'handle'  => self::IMAGES_LOADED,
			'source'  => 'assets/js/vendor/masonry/imagesloaded.pkgd.min.js',
			'version' => '4.1.0',
		),
		array(
			'handle'       => self::JQUERY_REACTIVE_IMAGES,
			'source'       => 'assets/js/reactive-images.umd.min.js',
			'dependencies' => array('jquery'),
			'version' => '1.0.1',
		),
		array(
			'handle'       => self::JQUERY_SCROLLMAGIC,
			'source'       => 'assets/js/vendor/scrollmagic/jquery.scrollmagic.min.js',
			'dependencies' => array('jquery', self::SCROLLMAGIC),
			'version'      => '2.0.5',
		),
		array(
			'handle'  => self::LAZYSIZES,
			'source'  => 'assets/js/vendor/lazysizes.min.js',
			'version' => '1.1.3',
		),
		array(
			'handle'       => self::MASONRY,
			'source'       => 'assets/js/vendor/masonry/masonry.pkgd.min.js',
			'dependencies' => array(self::IMAGES_LOADED),
			'version'      => '4.1.0',
			'override'     => true,
		),
		array(
			'handle'  => self::MODERNIZR,
			'source'  => 'assets/js/vendor/modernizr.min.js',
			'footer'  => false,
			'version' => '2.8.3',
		),
		array(
			'handle'  => self::PHOTOSWIPE,
			'source'  => 'assets/js/vendor/photoswipe/photoswipe.min.js',
			'version' => '4.0.8',
		),
		array(
			'handle'  => self::PHOTOSWIPE_UI,
			'source'  => 'assets/js/vendor/photoswipe/photoswipe-ui-default.min.js',
			'version' => '4.0.8',
		),
		array(
			'handle'  => self::SCROLLMAGIC,
			'source'  => 'assets/js/vendor/scrollmagic/scrollmagic.min.js',
			'version' => '2.0.5',
		),
		array(
			'handle'       => self::OM_GRID,
			'source'       => 'assets/js/grid.js',
			'dependencies' => array(self::MASONRY),
		),
		array(
			'handle'  => self::OM_LAZYSIZES,
			'source'  => 'assets/js/lazysizes.min.js',
			'dependencies' => array('jquery', self::LAZYSIZES),
		),
		array(
			'handle'       => self::OM_PHOTOSWIPE_LIGHTBOX,
			'source'       => 'assets/js/photoswipe-lightbox.min.js',
			'dependencies' => array(self::PHOTOSWIPE, self::PHOTOSWIPE_UI),
		),
		array(
			'handle'       => self::OM_SCROLL,
			'source'       => 'assets/js/scroll.min.js',
			'dependencies' => array('jquery', self::SCROLLMAGIC),
		),
	);
	
	protected static $_admin = array(
		array(
			'handle' => self::JQUERY_CHOSEN,
			'source' => 'assets/js/vendor/chosen.jquery.min.js',
		),
		array(
			'handle' => self::OM_BREAKPOINT_SELECT,
			'source' => 'assets/js/admin/breakpoint-select.js',
		),
		array(
			'handle' => self::OM_COLOR_ADVANCED,
			'source' => 'assets/js/admin/color-advanced.js',
		),
		array(
			'handle' => self::OM_DEVICE_SELECT,
			'source' => 'assets/js/admin/device-select.js',
		),
		array(
			'handle' => self::OM_IMAGE_PREVIEW,
			'source' => 'assets/js/admin/image-preview.js',
		),
	);
}