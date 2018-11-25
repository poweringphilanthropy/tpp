<?php

namespace OM\CC\Shared\Lib\Data\Customizer;

use OM\CC\Shared\Lib\Assets\Scripts;
use OM\CC\Shared\Lib\Assets\Styles;
use OM\CC\Shared\Lib\Html\HtmlWriter;

/**
 * Class CustomizerAdvansedColor
 * which add ColorPicker with Alpha
 * Class adapted from the Alpha Color Picker Customizer Control
 * @link https://github.com/BraadMartin/components/tree/master/customizer/alpha-color-picker
 */
class ColorAdvancedControl extends \WP_Customize_Control {
	
	public function __construct($manager, $id, $args = array()) {
		$this->type = 'color-advanced';
		
		parent::__construct($manager, $id, $args);
	}
	
	/**
	 * Enqueue scripts and styles.
	 */
	public function enqueue() {
		wp_enqueue_script(Scripts::OM_COLOR_ADVANCED);
		wp_enqueue_style(Styles::OM_COLOR_ADVANCED);
	}
	
	/**
	 * Return content render
	 */
	public function render_content() {
		$attributes = array(
			'type'                      => 'text',
			'data-om-cc-color-advanced' => 'true',
			'value'                     => $this->value(),
			'class'                     => 'pluto-color-control'
		);
		
		$link = $this->get_link();
		
		if ($link !== '') {
			$attributes['data-customize-setting-link'] = substr($link, 29, -1);
		}
		
		echo HtmlWriter::init()
		               ->label()
		               ->span(array('class' => 'customize-control-title'), esc_html($this->label), true)
		               ->input($attributes, true)
		               ->end();
	}
	
}
