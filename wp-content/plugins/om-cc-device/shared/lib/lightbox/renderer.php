<?php

namespace OM\CC\Shared\Lib\Lightbox;

use OM\CC\Shared\Lib\Html\CssWriter;
use OM\CC\Shared\Lib\System\Singleton;

class Renderer extends Singleton {
	
	/**
	 * @var array
	 */
	private $settings = array();
	
	/**
	 * @var array
	 */
	private $_shareTexts;
	
	protected function __construct() {
		parent::__construct();
		
		$this->_shareTexts = array(
			'download'    => esc_html__('Download image', OM_CP_SHARED_TEXTDOMAIN),
			'facebook'    => esc_html__('Share on Facebook', OM_CP_SHARED_TEXTDOMAIN),
			'twitter'     => esc_html__('Tweet', OM_CP_SHARED_TEXTDOMAIN),
			'pinterest'   => esc_html__('Pin it', OM_CP_SHARED_TEXTDOMAIN),
			'tumblr'      => esc_html__('Share on Tumblr', OM_CP_SHARED_TEXTDOMAIN),
			'google-plus' => esc_html__('Share on Google+', OM_CP_SHARED_TEXTDOMAIN),
			'vk'          => esc_html__('Share on VK', OM_CP_SHARED_TEXTDOMAIN),
			'reddit'      => esc_html__('Reddit this', OM_CP_SHARED_TEXTDOMAIN),
		);
		
		add_action('wp_head', array($this, '__onHeadRender'));
	}
	
	public function __onHeadRender() {
		echo $this->_getMeta(),
			'<style type="text/css">' . $this->_getCss() . '</style>';
	}
	
	public static function create(array $settings) {
		self::getInstance()->settings = $settings;
	}
	
	/**
	 * @return string
	 */
	private function _getCss() {
		$settings = $this->settings;
		
		$css = CssWriter::init();
		$css->set(array(
			'#photoswipe .pswp__bg',
			'#photoswipe .pswp--zoomed-in .pswp__top-bar,#photoswipe .pswp--zoomed-in .pswp__caption'
		), 'background-color', $settings['background']);
		$css->set('#photoswipe .pswp__button', 'color', $settings['button_color']);
		$css->set('#photoswipe .pswp__button--arrow--left,#photoswipe .pswp__button--arrow--right', 'color', $settings['arrow_color']);
		$css->set('#photoswipe .pswp__button--arrow--left,#photoswipe .pswp__button--arrow--right', 'background-color', $settings['arrow_background']);
		$css->set('#photoswipe .pswp__button--arrow--left:hover,#photoswipe .pswp__button--arrow--right:hover', 'background-color', $settings['arrow_background_hover']);
		$css->set('#photoswipe .pswp__counter', 'color', $settings['counter_color']);
		$css->set('#photoswipe .pswp__caption', 'color', $settings['caption_color']);
		$css->set('#photoswipe .pswp__share-modal .pswp__share-tooltip a', 'color', $settings['share_color']);
		$css->set('#photoswipe .pswp__share-modal .pswp__share-tooltip a', 'background-color', $settings['share_background']);
		$css->set('#photoswipe .pswp__share-modal', 'background-color', $settings['overlay_color']);
		$css->set('#photoswipe .pswp__img--placeholder--blank', 'background-color', $settings['image_background_color']);
		
		return (string)$css;
	}
	
	/**
	 * @return string
	 */
	private function _getMeta() {
		$settings = $this->settings;
		
		$data = array(
			'share' => array()
		);
		
		if ($settings['fullscreen']) {
			$data['fullscreen'] = (bool)$settings['fullscreen'];
		}
		
		if ($settings['zoom']) {
			$data['zoom'] = (bool)$settings['zoom'];
		}
		
		foreach ($this->_shareTexts as $share => $text) {
			if ($settings['share_' . str_replace('-', '_', $share)]) {
				$data['share'][$share] = $text;
			}
		}
		
		if (count($data['share']) === 0) {
			unset ($data['share']);
		}
		
		$data = apply_filters('om_plugins_lightbox_settings', $data);
		
		return count($data) !== 0 ? '<meta name="photoswipe" content="' . esc_attr(json_encode($data)) . '"/>' : '';
	}
}