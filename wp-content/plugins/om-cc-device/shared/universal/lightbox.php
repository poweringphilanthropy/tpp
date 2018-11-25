<?php
namespace OM\CC\Shared\Universal;

use OM\CC\Shared\Lib\Data\Customizer;
use OM\CC\Shared\Lib\Lightbox\Common as LightboxCommon;
use OM\CC\Shared\Lib\Lightbox\Renderer as LightboxRenderer;

class Lightbox extends UniversalUnitary {
	
	/**
	 * @var string
	 */
	private static $_panelPrefix = 'cc_';
	
	/**
	 * @var string
	 */
	private static $_sectionPrefix = 'cc_';
	
	/**
	 * @var string
	 */
	private static $_prefix = 'colors-creative_lightbox_';
	
	protected static function _initOnce() {
		$config = self::_getConfig();
		
		Customizer::getInstance()->add($config);
		LightboxRenderer::create(self::_getSettings($config));
	}
	
	/**
	 * @return array[]
	 */
	private static function _getConfig() {

		$initialConfig = LightboxCommon::getConfig();
		$config        = array();

		foreach ($initialConfig as $panelId => $panel) {
			
			$prefixedPanel = array(
				'name'     => $panel['name'],
				'position' => $panel['position'],
			);
			foreach ((array)$panel['sections'] as $sectionId => $section) {
				
				$prefixedSection = array(
					'name'     => $section['name'],
					'position' => $section['position'],
				);
				foreach ((array)$section['options'] as $option) {
					$option['id']      = self::$_prefix . $option['id'];
					$prefixedSection['options'][] = $option;
				}
				
				$prefixedPanel['sections'][self::$_sectionPrefix . $sectionId] = $prefixedSection;
			}
			
			$config[self::$_panelPrefix . $panelId] = $prefixedPanel;
		}
		
		return $config;
	}
	
	/**
	 * Return settings array
	 *
	 * @param array[] $config
	 *
	 * @return array
	 */
	private static function _getSettings(array $config) {
		$prefix = self::$_prefix;
		
		$settings = array();
		
		foreach ($config as $panel) {
			foreach ((array)$panel['sections'] as $section) {
				foreach ((array)$section['options'] as $option) {
					$idWoPrefix = str_replace($prefix, '', $option['id']);
					$settings[$idWoPrefix] = get_theme_mod($option['id']);
				}
			}
		}
		
		return $settings;
	}
}