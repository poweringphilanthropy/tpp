<?php

namespace OM\CC\Shared\Lib\Data\MetaBox;

use OM\CC\Shared\Lib\Assets\Assets;
use OM\CC\Shared\Lib\Assets\Scripts;
use OM\CC\Shared\Lib\Assets\Styles;
use OM\CC\Shared\Lib\Html\HtmlWriter;
use OM\CC\Shared\Lib\Html\Images;

class ImageControl extends BaseControl {
	
	public function __construct($id, $title, $placeholder = '', $description = '') {
		parent::__construct($id, $title, 'image', $placeholder, $description);
		
		Assets::getInstance()
		      ->enqueueStyles(array(Styles::OM_IMAGE_PREVIEW))
		      ->enqueueScripts(array(Scripts::OM_IMAGE_PREVIEW));
	}
	
	public function render($controlId, $value) {
		
		$id = (int)$value;
		
		$html = HtmlWriter::init();
		
		$html->div(array('data-om-cc-image-preview' => '', 'class' => 'om-cc-image-preview'))
		     ->input(array(
			     'id'    => $controlId,
			     'name'  => $controlId,
			     'value' => $value,
			     'type'  => 'hidden',
		     ), true);
		
		$html->div(array('class' => 'thumbnail'));
		
		if (Images::exists($id)) {
			
			$imageInfo = Images::getInfo($id, array(array(150, 150)));
			$sizeInfo  = reset($imageInfo['sizes']);
			
			$html->i('class="dashicons dashicons-no-alt remove"', '', true)
			     ->img(array(
				     'src'   => $sizeInfo['url'],
				     'alt'   => $imageInfo['alt'],
				     'style' => "width:{$sizeInfo['width']}px;",
			     ), true);
		} else {
			$html->span('class="add-img"', esc_html__('Add image', OM_CP_SHARED_TEXTDOMAIN), true);
		}
		
		$html->end(2);
		
		return (string)$html;
	}
}