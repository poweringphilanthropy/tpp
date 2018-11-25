<?php

namespace OM\CC\Shared\Lib\Data\MetaBox;

use OM\CC\Shared\Lib\Assets\Assets;
use OM\CC\Shared\Lib\Assets\Scripts;
use OM\CC\Shared\Lib\Html\HtmlWriter;

class DeviceControl extends BaseControl {
	
	/**
	 * @var array
	 */
	public $devicesVariants;
	
	public function __construct($id, $title, $devicesData, $placeholder = '', $description = '') {
		parent::__construct($id, $title, 'device', $placeholder, $description);
		$this->devicesVariants = $devicesData;
		
		Assets::getInstance()->enqueueScripts(array(Scripts::OM_DEVICE_SELECT));
	}
	
	public function render($controlId, $value) {
		$devicesVariants = str_replace('om-cc-', '', json_encode($this->devicesVariants));
		$html          = HtmlWriter::init();
		$html->label(array('class' => 'screen-reader-text', 'for' => $controlId), $this->title, true)
		     ->input(array(
			     'name'                     => $controlId,
			     'type'                     => 'hidden',
			     'id'                       => $controlId,
			     'value'                    => $value,
			     'data-om-cc-device-select' => esc_attr($devicesVariants)
		     ), true);
		
		return (string)$html;
	}
}