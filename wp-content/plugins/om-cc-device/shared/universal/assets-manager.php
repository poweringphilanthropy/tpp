<?php

namespace OM\CC\Shared\Universal;

use OM\CC\Shared\Lib\Assets\Assets;
use OM\CC\Shared\Lib\Assets\Scripts;
use OM\CC\Shared\Lib\Assets\Styles;

class AssetsManager extends UniversalUnitary {
	
	/**
	 * @var string[]
	 */
	private static $DEFAULT_STYLES = array(
		Styles::IONICONS,
		Styles::CHOSEN,
		Styles::OM_PARAMS,
	);
	
	/**
	 * @var string[]
	 */
	private static $DEFAULT_SCRIPTS = array(
		Scripts::JQUERY_CHOSEN,
		Scripts::MASONRY,
	);
	
	/**
	 * @var integer
	 */
	private static $features;
	
	/**
	 * Initialize.
	 *
	 * @param integer $features
	 */
	public static function init($features = Features::NONE) {
		self::$features = $features;
		
		parent::init();
	}
	
	protected static function _initOnce() {
		Assets::getInstance()
		      ->registerStyles(Styles::all(self::$_sharedUrl, self::$_sharedVersion))
		      ->registerScripts(Scripts::all(self::$_sharedUrl, self::$_sharedVersion))
		      ->enqueueStyles(self::$DEFAULT_STYLES)
		      ->enqueueScripts(self::$DEFAULT_SCRIPTS);
	}
	
	protected static function _initEver() {
		if (self::$features & Features::GRID) {
			Assets::getInstance()
			      ->enqueueScripts(array(
				      Scripts::IMAGES_LOADED,
				      Scripts::MASONRY,
				      Scripts::OM_GRID
			      ));
		}
		
		if (self::$features & Features::REACTIVE_IMAGES) {
			Assets::getInstance()
			      ->enqueueScripts(array(
				      Scripts::LAZYSIZES,
				      Scripts::OM_LAZYSIZES
			      ));
		}
		
		if (self::$features & Features::LIGHTBOX) {
			Assets::getInstance()
			      ->enqueueStyles(array(
				      Styles::IONICONS,
				      Styles::OM_ARROWS,
				      Styles::OM_PHOTOSWIPE_LIGHTBOX
			      ))
			      ->enqueueScripts(array(
				      Scripts::MODERNIZR,
				      Scripts::PHOTOSWIPE,
				      Scripts::PHOTOSWIPE_UI,
				      Scripts::OM_PHOTOSWIPE_LIGHTBOX
			      ));
		}
		
		if (self::$features & Features::SCROLL) {
			Assets::getInstance()
			      ->enqueueScripts(array(
				      Scripts::JQUERY_SCROLLMAGIC,
				      Scripts::SCROLLMAGIC,
				      Scripts::OM_SCROLL
			      ));
		}

		if (self::$features & Features::REACTIVE_IMAGES) {
			Assets::getInstance()
			      ->enqueueScripts(array(
				      Scripts::JQUERY_REACTIVE_IMAGES
			      ));
		}
	}
}