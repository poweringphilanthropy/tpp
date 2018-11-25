<?php

namespace OM\CC\Shared\Lib\Data;

use OM\CC\Shared\Lib\Data\Customizer\ColorAdvancedControl;
use OM\CC\Shared\Lib\System\Singleton;
use WP_Customize_Manager;

class Customizer extends Singleton {
	
	/**
	 * Customizer config
	 * @var array[]
	 */
	private $_config = array();
	
	/**
	 * Customizer constructor.
	 */
	protected function __construct() {
		parent::__construct();
		
		add_action('customize_register', array($this, '__register'));
	}
	
	/**
	 * @param array[] $config
	 */
	public function add(array $config) {
		$this->_config = array_merge_recursive($this->_config, $config);
	}
	
	/**
	 * Create the customizer panels
	 *
	 * @param WP_Customize_Manager $customizeManager -- Customizer manager
	 */
	public function __register(WP_Customize_Manager $customizeManager) {
		$wpPanels   = $customizeManager->panels();
		$wpSections = $customizeManager->sections();
		
		foreach ($this->_config as $panelId => $panel) {
			
			if (!array_key_exists($panelId, $wpPanels) && $panelId !== 'default') {
				$wpPanels[] = $customizeManager->add_panel($panelId, array(
					'title'       => $panel['name'],
					'priority'    => $panel['position'],
					'capability'  => array_key_exists('capability', $panel) ? $panel['capability'] : 'edit_theme_options',
					'description' => array_key_exists('description', $panel) ? $panel['description'] : '',
				));
			}
			
			foreach ((array)$panel['sections'] as $sectionId => $section) {
				
				if (!array_key_exists($sectionId, $wpSections)) {
					$wpSections[] = $customizeManager->add_section($sectionId, array(
						'title'       => $section['name'],
						'priority'    => $section['position'],
						'description' => array_key_exists('description', $section) ? $section['description'] : '',
						'capability'  => array_key_exists('capability', $section) ? $section['capability'] : 'edit_theme_options',
						'panel'       => $panelId,
					));
				}
				
				foreach ((array)$section['options'] as $index => $option) {
					$customizeManager->add_setting($option['id'], array(
						'default' => array_key_exists('default', $option) ? $option['default'] : ''
					));
					
					$control = $this->_getControl($customizeManager, $option, $sectionId);
					
					$customizeManager->add_control($control);
				}
			}
		}
	}
	
	/**
	 * Get controls
	 *
	 * @param WP_Customize_Manager $customizeManager -- Customizer manager
	 * @param array                $option           -- Customizer's control options from config
	 * @param string               $sectionId        -- Section ID
	 *
	 * @return \WP_Customize_Control
	 */
	private function _getControl(WP_Customize_Manager $customizeManager, array $option, $sectionId) {
		
		$arguments = array(
			'section'  => $sectionId,
			'settings' => $option['id'],
			'label'    => array_key_exists('name', $option) ? $option['name'] : null,
			'type'     => array_key_exists('type', $option) ? $option['type'] : null
		);
		
		$id = $option['id'];
		
		switch ($option['type']) {
			case 'color':
				$control = new \WP_Customize_Color_Control($customizeManager, $id, $arguments);
				break;
			case 'color-advanced':
				$control = new ColorAdvancedControl($customizeManager, $id, $arguments);
				break;
			default:
				$control = new \WP_Customize_Control($customizeManager, $id, $arguments);
				break;
		}
		
		return $control;
	}
}