<?php

namespace OM\CC\Shared\Universal;

define('OM_CP_SHARED_TEXTDOMAIN', 'om-cp-shared');

class TranslationManager extends UniversalUnitary {

	protected static function _initOnce() {
		load_plugin_textdomain(OM_CP_SHARED_TEXTDOMAIN, false, self::$_sharedPath . '/languages/');
	}
}