<?php

namespace OM\CC\Shared\Lib\Html;

use OM\CC\Shared\Lib\Utils\Utils;

/**
 * Class HTML Writer
 * Document metadata
 * @method HtmlWriter base(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter head(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter link(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter meta(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter style(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter title(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * Content sectioning
 * @method HtmlWriter address(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter article(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter aside(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter footer(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter header(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter h1(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter h2(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter h3(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter h4(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter h5(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter h6(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter hgroup(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter nav(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * Text content
 * @method HtmlWriter dd(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter div(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter dl(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter dt(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter figcaption(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter figure(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter hr(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter li(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter main(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter ol(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter p(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter pre(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter ul(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * Inline text semantics
 * @method HtmlWriter a(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter abbr(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter b(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter bdi(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter bdo(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter br(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter cite(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter code(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter data(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter dfn(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter em(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter i(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter kbd(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter mark(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter q(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter rp(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter rt(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter rtc(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter ruby(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter s(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter samp(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter small(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter span(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter strong(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter sub(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter sup(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter time(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter u(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter var(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter wbr(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * Image and multimedia
 * @method HtmlWriter area(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter audio(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter img(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter map(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter track(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter video(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * Embedded content
 * @method HtmlWriter embed(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter object(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter param(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter source(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * Scripting
 * @method HtmlWriter canvas(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter noscript(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter script(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * Demarcating edits
 * @method HtmlWriter del(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter ins(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * Table content
 * @method HtmlWriter caption(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter col(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter colgroup(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter table(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter tbody(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter td(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter tfoot(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter th(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter thead(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter tr(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * Forms
 * @method HtmlWriter button(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter datalist(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter fieldset(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter form(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter input(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter label(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter legend(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter meter(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter optgroup(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter option(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter output(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter progress(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter select(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter textarea(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * Interactive elements
 * @method HtmlWriter details(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter dialog(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter menu(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter menuitem(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter summary(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * Web Components
 * @method HtmlWriter content(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter element(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter shadow(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method HtmlWriter template(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 */
class HtmlWriter {
	
	/**
	 * Content
	 * @var string
	 */
	private $_html = '';
	
	/**
	 * Array of non-closed tags
	 * @var array
	 */
	private $_closes = array();
	
	/**
	 * Create new element
	 *
	 * @param string       $tag        Tag name
	 * @param string|array $attributes Attributes
	 * @param string       $text
	 * @param string       $closed
	 *
	 * @return $this
	 */
	private function _addElement($tag, $attributes = '', $text, $closed) {
		$endTag   = "</$tag>";
		$slash    = '';
		$closeTag = '';
		
		if ($closed) {
			if (null !== $text) {
				$closeTag = $endTag;
			} else {
				$slash = '/';
			}
		} else {
			$this->_closes[] = $endTag;
		}
		
		$this->_html .= "<$tag{$attributes}{$slash}>$text{$closeTag}";
		
		return $this;
	}
	
	/**
	 * Create new instance of Html class
	 * @return $this
	 */
	public static function init() {
		return new HtmlWriter();
	}
	
	/**
	 * Clear all
	 * @return $this
	 */
	public function clear() {
		$this->_html   = '';
		$this->_closes = array();
		
		return $this;
	}
	
	/**
	 * Close last opened tag or some number tags
	 *
	 * @param boolean|int $all if true than all tags, if number than count, otherwise one
	 *
	 * @return $this
	 */
	public function end($all = false) {
		if ($all) {
			$all = is_int($all) ? $all : count($this->_closes);
			
			while ($this->_closes && $all--) {
				$this->_html .= array_pop($this->_closes);
			}
		} else if (0 !== count($this->_closes)) {
			$this->_html .= array_pop($this->_closes);
		}
		
		return $this;
	}
	
	/**
	 * Insert an html
	 *
	 * @param HtmlWriter[]|HtmlWriter $html an HtmlWriter object or array of its
	 *
	 * @return $this
	 */
	public function html($html) {
		if (!is_array($html)) {
			$html = array($html);
		}
		
		foreach ((array)$html as $part) {
			$part = (object)$part;
			
			$this->_html .= (string)$part;
		}
		
		return $this;
	}
	
	/**
	 * Insert text
	 *
	 * @param string $text
	 *
	 * @return $this
	 */
	public function text($text) {
		$this->_html .= $text;
		
		return $this;
	}
	
	/**
	 * Return finalized html string
	 * @return string
	 */
	public function __toString() {
		return $this->end(true)->_html;
	}
	
	/**
	 * Magic method used to generate HTML tags
	 *
	 * @param string $name      name of called function
	 * @param array  $arguments array of parameters
	 *
	 * @return $this
	 */
	public function __call($name, $arguments) {
		$last = end($arguments);
		
		$attributes = '';
		$closedTag  = false;
		$text       = null;
		
		if (true === $last) {
			$closedTag = true;
			array_pop($arguments);
		}
		
		$count = count($arguments);
		
		if ($count > 0) {
			$attributes = ' ';
			
			if (is_array($arguments[0])) {
				if (array_key_exists('text', $arguments[0])) {
					$text = $arguments[0]['text'];
					unset($arguments[0]['text']);
				}
				
				$attributes .= Utils::getAttributesString($arguments[0]);
			} else {
				$attributes .= $arguments[0];
			}
			
			if ($count > 1) {
				$text = (string)$arguments[1];
			}
		}
		
		$this->_addElement($name, $attributes, $text, $closedTag);
		
		return $this;
	}
}