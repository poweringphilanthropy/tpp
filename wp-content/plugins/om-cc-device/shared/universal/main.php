<?php

namespace OM\CC\Shared\Universal;

class Main {
	
	
	/**
	 * @param integer $features Mask of requested Features
	 */
	public static function init($features = Features::NONE) {
		TranslationManager::init();
		ApiRoutes::init();
		AssetsManager::init($features);
		ParamsManager::init();
		
		if ($features | Features::LIGHTBOX) {
			Lightbox::init();
		}
	}
}