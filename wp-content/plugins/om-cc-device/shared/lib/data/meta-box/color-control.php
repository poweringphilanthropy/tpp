<?php

namespace OM\CC\Shared\Lib\Data\MetaBox;

use OM\CC\Shared\Lib\Assets\Assets;
use OM\CC\Shared\Lib\Assets\Scripts;
use OM\CC\Shared\Lib\Assets\Styles;
use OM\CC\Shared\Lib\Html\HtmlWriter;

class ColorControl extends BaseControl {
	
	public function __construct($id, $title, $placeholder = '', $description = '') {
		parent::__construct($id, $title, 'colorpicker', $placeholder, $description);
		
		Assets::getInstance()
		      ->enqueueStyles(array(Styles::OM_COLOR_ADVANCED))
		      ->enqueueScripts(array(Scripts::OM_COLOR_ADVANCED));
	}
	
	public function render($controlId, $value) {
		$html = HtmlWriter::init();
		$html->label(array('class' => 'screen-reader-text', 'for' => $controlId), $this->title, true)
		     ->input(array(
			     'name'                      => $controlId,
			     'class'                     => 'pluto-color-control',
			     'type'                      => 'text',
			     'id'                        => $controlId,
			     'value'                     => $value,
			     'data-om-cc-color-advanced' => 'true',
		     ), true);
		
		return (string)$html;
	}
}