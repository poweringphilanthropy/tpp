<?php

namespace OM\CC\Shared\Universal;

use OM\CC\Shared\Lib\Common\Params;

class ParamsManager extends UniversalUnitary {
	
	protected static function _initOnce() {
		Params::getInstance()->init(self::$_sharedUrl);
	}
}