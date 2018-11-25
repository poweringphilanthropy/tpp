<?php

namespace OM\CC\Shared\Lib\Data\MetaBox;

abstract class BaseControl {
	
	/**
	 * @var string
	 */
	public $id;
	
	/**
	 * @var string
	 */
	public $title;
	
	/**
	 * @var string
	 */
	public $type;
	
	/**
	 * @var string
	 */
	public $description;
	
	/**
	 * @var string
	 */
	public $placeholder;
	
	/**
	 * @var string
	 */
	public $capability = 'edit_posts';
	
	public function __construct($id, $title, $type, $placeholder = '', $description = '') {
		$this->id          = $id;
		$this->title       = $title;
		$this->type        = $type;
		$this->placeholder = $placeholder;
		$this->description = $description;
	}
	
	/**
	 * @param string $controlId
	 * @param mixed  $value
	 *
	 * @return mixed
	 */
	abstract public function render($controlId, $value);
}