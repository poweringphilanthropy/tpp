<?php

namespace OM\CC\Shared\Lib\Data\MetaBox;

use OM\CC\Shared\Lib\Html\HtmlWriter;

class TextControl extends BaseControl {
	
	public function __construct($id, $title, $placeholder = '', $description = '') {
		parent::__construct($id, $title, 'text', $placeholder, $description);
	}
	
	public function render($controlId, $value) {
		$html = HtmlWriter::init();
		$html->label(array('class' => 'screen-reader-text', 'for' => $controlId), $this->title, true)
		     ->input(array(
			     'name'  => $controlId,
			     'class' => 'widefat',
			     'type'  => 'text',
			     'size'  => '4',
			     'id'    => $controlId,
			     'value' => $value
		     ), true);
		
		return (string)$html;
	}
}