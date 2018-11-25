<?php

namespace OM\CC\Shared\Lib\Data\MetaBox;

use OM\CC\Shared\Lib\Assets\Assets;
use OM\CC\Shared\Lib\Assets\Scripts;
use OM\CC\Shared\Lib\Html\HtmlWriter;

class BreakpointControl extends BaseControl {
	/**
	 * @var array
	 */
	private $breakpoints;
	
	/**
	 * BreakpointControl constructor.
	 *
	 * @param string $id
	 * @param string $title
	 * @param array  $breakpoints
	 * @param string $placeholder
	 * @param string $description
	 */
	public function __construct($id, $title, array $breakpoints, $placeholder = '', $description = '') {
		parent::__construct($id, $title, 'breakpoint', $placeholder, $description);
		
		$this->breakpoints = $breakpoints;
		
		Assets::getInstance()->enqueueScripts(array(Scripts::OM_BREAKPOINT_SELECT));
	}
	
	public function render($controlId, $value) {
		$html = HtmlWriter::init();
		$html->label(array('class' => 'screen-reader-text', 'for' => $controlId), $this->title, true)
		     ->input(array(
			     'name'                         => $controlId,
			     'type'                         => 'hidden',
			     'id'                           => $controlId,
			     'value'                        => $value,
			     'data-om-cc-breakpoint-select' => json_encode($this->breakpoints),
		     ), true);
		
		return (string)$html;
	}
}