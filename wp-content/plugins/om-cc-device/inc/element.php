<?php

namespace OM\CC\Plugins\Device;

use OM\CC\Shared\Lib\Common\BaseElement;
use OM\CC\Shared\Lib\Html\HtmlWriter;

//use OM\CC\Shared\Lib\Utils\Assets;

class Element extends BaseElement {
	
	protected function __construct() {
		parent::__construct();
		
		$this->_version   = OM_CC_VC_DEVICE_VERSION;
		$this->_stylesUrl = OM_CC_VC_DEVICE_URL . 'assets/css/styles.min.css';
	}
	
	public function init() {
		$general = esc_html__('General', OM_CC_VC_DEVICE_TEXTDOMAIN);

		$this->parameters = array(
			'category'                => esc_html__('Colors Creative', OM_CC_VC_DEVICE_TEXTDOMAIN),
			'name'                    => esc_html__('Creative Devices Mock-ups', OM_CC_VC_DEVICE_TEXTDOMAIN),
			'description'             => esc_html__('The most creative way to display content in devices mock-ups', OM_CC_VC_DEVICE_TEXTDOMAIN),
			'icon'                    => OM_CC_VC_DEVICE_URL . 'assets/img/devices-icon.png',
			'as_parent'               => array('except' => 'vc_row,om_cc_vc_device_element'),
			'content_element'         => true,
			'show_settings_on_create' => true,
			'params'                  => array(
				array(
					'group' => $general,
					'param_name' => 'device_type',
					'type'       => 'dropdown',
					'heading'    => esc_html__('Device type', OM_CC_VC_DEVICE_TEXTDOMAIN),
					'value'      => array(
						esc_html__('Flat', OM_CC_VC_DEVICE_TEXTDOMAIN)     => 'om-cc-flat',
						esc_html__('Real', OM_CC_VC_DEVICE_TEXTDOMAIN)     => 'om-cc-real',
						esc_html__('Colorful', OM_CC_VC_DEVICE_TEXTDOMAIN) => 'om-cc-colorful',
					),
					'std'        => 'om-cc-flat',
				),
				/*flat*/
				array(
					'group' => $general,
					'param_name'  => 'device_flat_mockup',
					'type'        => 'dropdown',
					'heading'     => esc_html__('Select mockup', OM_CC_VC_DEVICE_TEXTDOMAIN),
					'description' => esc_html__('Aspect ratio: Browser - 4/3, Desktop - 16/9, Laptop - 16/10, Phone - 16/9, Tablet - 4/3, Watch - 5/4', OM_CC_VC_DEVICE_TEXTDOMAIN),
					'value'       => array(
						esc_html__('Browser', OM_CC_VC_DEVICE_TEXTDOMAIN)          => 'om-cc-browser',
						esc_html__('Desktop', OM_CC_VC_DEVICE_TEXTDOMAIN)          => 'om-cc-desktop',
						esc_html__('Laptop', OM_CC_VC_DEVICE_TEXTDOMAIN)           => 'om-cc-laptop',
						esc_html__('Phone Landscape', OM_CC_VC_DEVICE_TEXTDOMAIN)  => 'om-cc-phone-landscape',
						esc_html__('Phone Portrait', OM_CC_VC_DEVICE_TEXTDOMAIN)   => 'om-cc-phone-portrait',
						esc_html__('Tablet Landscape', OM_CC_VC_DEVICE_TEXTDOMAIN) => 'om-cc-tablet-landscape',
						esc_html__('Tablet Portrait', OM_CC_VC_DEVICE_TEXTDOMAIN)  => 'om-cc-tablet-portrait',
						esc_html__('Watch', OM_CC_VC_DEVICE_TEXTDOMAIN)            => 'om-cc-watch',
					),
					'std'         => 'om-cc-browser',
					'dependency'  => array(
						'element' => 'device_type',
						'value'   => 'om-cc-flat',
					),
				),
				array(
					'group' => $general,
					'param_name' => 'device_flat_browser_color',
					'type'       => 'dropdown',
					'heading'    => esc_html__('Select color', OM_CC_VC_DEVICE_TEXTDOMAIN),
					'value'      => array(
						esc_html__('Dark', OM_CC_VC_DEVICE_TEXTDOMAIN)  => 'om-cc-dark',
						esc_html__('Light', OM_CC_VC_DEVICE_TEXTDOMAIN) => 'om-cc-light',
					),
					'std'        => 'om-cc-dark',
					'dependency' => array(
						'element' => 'device_flat_mockup',
						'value'   => array(
							'om-cc-browser',
							'om-cc-desktop',
							'om-cc-tablet-landscape',
							'om-cc-tablet-portrait'
						),
					),
				),
				array(
					'group' => $general,
					'param_name' => 'device_flat_laptop_color',
					'type'       => 'dropdown',
					'heading'    => esc_html__('Select color', OM_CC_VC_DEVICE_TEXTDOMAIN),
					'value'      => array(
						esc_html__('Dark', OM_CC_VC_DEVICE_TEXTDOMAIN)              => 'om-cc-dark',
						esc_html__('Light', OM_CC_VC_DEVICE_TEXTDOMAIN)             => 'om-cc-light',
						esc_html__('Alternative Dark', OM_CC_VC_DEVICE_TEXTDOMAIN)  => 'om-cc-alternative-dark',
						esc_html__('Alternative Light', OM_CC_VC_DEVICE_TEXTDOMAIN) => 'om-cc-alternative-light',
					),
					'std'        => 'om-cc-dark',
					'dependency' => array(
						'element' => 'device_flat_mockup',
						'value'   => 'om-cc-laptop',
					),
				),
				array(
					'group' => $general,
					'param_name' => 'device_flat_phone_landscape_color',
					'type'       => 'dropdown',
					'heading'    => esc_html__('Select color', OM_CC_VC_DEVICE_TEXTDOMAIN),
					'value'      => array(
						esc_html__('Dark', OM_CC_VC_DEVICE_TEXTDOMAIN)       => 'om-cc-dark',
						esc_html__('Light', OM_CC_VC_DEVICE_TEXTDOMAIN)      => 'om-cc-light',
						esc_html__('Gold', OM_CC_VC_DEVICE_TEXTDOMAIN)       => 'om-cc-gold',
						esc_html__('Rose', OM_CC_VC_DEVICE_TEXTDOMAIN)       => 'om-cc-rose',
						esc_html__('Silver', OM_CC_VC_DEVICE_TEXTDOMAIN)     => 'om-cc-silver',
						esc_html__('Space Grey', OM_CC_VC_DEVICE_TEXTDOMAIN) => 'om-cc-grey',
					),
					'std'        => 'om-cc-dark',
					'dependency' => array(
						'element' => 'device_flat_mockup',
						'value'   => 'om-cc-phone-landscape',
					),
				),
				array(
					'group' => $general,
					'param_name' => 'device_flat_phone_portrait_color',
					'type'       => 'dropdown',
					'heading'    => esc_html__('Select color', OM_CC_VC_DEVICE_TEXTDOMAIN),
					'value'      => array(
						esc_html__('Dark', OM_CC_VC_DEVICE_TEXTDOMAIN)       => 'om-cc-dark',
						esc_html__('Light', OM_CC_VC_DEVICE_TEXTDOMAIN)      => 'om-cc-light',
						esc_html__('Gold', OM_CC_VC_DEVICE_TEXTDOMAIN)       => 'om-cc-gold',
						esc_html__('Rose', OM_CC_VC_DEVICE_TEXTDOMAIN)       => 'om-cc-rose',
						esc_html__('Silver', OM_CC_VC_DEVICE_TEXTDOMAIN)     => 'om-cc-silver',
						esc_html__('Space Grey', OM_CC_VC_DEVICE_TEXTDOMAIN) => 'om-cc-grey',
					),
					'std'        => 'om-cc-dark',
					'dependency' => array(
						'element' => 'device_flat_mockup',
						'value'   => 'om-cc-phone-portrait',
					),
				),
				array(
					'group' => $general,
					'param_name' => 'device_flat_watch_color',
					'type'       => 'dropdown',
					'heading'    => esc_html__('Select color', OM_CC_VC_DEVICE_TEXTDOMAIN),
					'value'      => array(
						esc_html__('Dark', OM_CC_VC_DEVICE_TEXTDOMAIN)   => 'om-cc-dark',
						esc_html__('Light', OM_CC_VC_DEVICE_TEXTDOMAIN)  => 'om-cc-light',
						esc_html__('Blue', OM_CC_VC_DEVICE_TEXTDOMAIN)   => 'om-cc-blue',
						esc_html__('Red', OM_CC_VC_DEVICE_TEXTDOMAIN)    => 'om-cc-red',
						esc_html__('Yellow', OM_CC_VC_DEVICE_TEXTDOMAIN) => 'om-cc-yellow',
					),
					'std'        => 'om-cc-dark',
					'dependency' => array(
						'element' => 'device_flat_mockup',
						'value'   => 'om-cc-watch',
					),
				),
				/*real*/
				array(
					'group' => $general,
					'param_name'  => 'device_real_mockup',
					'type'        => 'dropdown',
					'heading'     => esc_html__('Select mockup', OM_CC_VC_DEVICE_TEXTDOMAIN),
					'description' => esc_html__('Aspect ratio: Browser - 4/3, Display - 16/9, Desktop - 16/9, Laptop - 16/10, Phone - 16/9, Tablet - 4/3, Watch - 5/4', OM_CC_VC_DEVICE_TEXTDOMAIN),
					'value'       => array(
						
						esc_html__('Laptop', OM_CC_VC_DEVICE_TEXTDOMAIN)            => 'om-cc-laptop',
						esc_html__('Phone ', OM_CC_VC_DEVICE_TEXTDOMAIN)            => 'om-cc-phone',
						esc_html__('Phone Landscape', OM_CC_VC_DEVICE_TEXTDOMAIN)   => 'om-cc-phone-landscape',
						esc_html__('Tablet', OM_CC_VC_DEVICE_TEXTDOMAIN)            => 'om-cc-tablet',
						esc_html__('Tablet  Landscape', OM_CC_VC_DEVICE_TEXTDOMAIN) => 'om-cc-tablet-landscape',
						esc_html__('Watch', OM_CC_VC_DEVICE_TEXTDOMAIN)             => 'om-cc-watch',
						esc_html__('Browser', OM_CC_VC_DEVICE_TEXTDOMAIN)           => 'om-cc-browser',
						esc_html__('Desktop', OM_CC_VC_DEVICE_TEXTDOMAIN)           => 'om-cc-desktop',
						esc_html__('Display', OM_CC_VC_DEVICE_TEXTDOMAIN)           => 'om-cc-display',
					),
					'std'         => 'om-cc-laptop',
					'dependency'  => array(
						'element' => 'device_type',
						'value'   => 'om-cc-real',
					),
				),
				array(
					'group' => $general,
					'param_name' => 'device_real_laptop_color',
					'type'       => 'dropdown',
					'heading'    => esc_html__('Select color', OM_CC_VC_DEVICE_TEXTDOMAIN),
					'value'      => array(
						esc_html__('Gold', OM_CC_VC_DEVICE_TEXTDOMAIN)        => 'om-cc-gold',
						esc_html__('Silver', OM_CC_VC_DEVICE_TEXTDOMAIN)      => 'om-cc-silver',
						esc_html__('Space Grey', OM_CC_VC_DEVICE_TEXTDOMAIN)  => 'om-cc-grey',
						esc_html__('Alternative', OM_CC_VC_DEVICE_TEXTDOMAIN) => 'om-cc-alternative',
					),
					'std'        => 'om-cc-gold',
					'dependency' => array(
						'element' => 'device_real_mockup',
						'value'   => 'om-cc-laptop',
					),
				),
				array(
					'group' => $general,
					'param_name' => 'device_real_phone_color',
					'type'       => 'dropdown',
					'heading'    => esc_html__('Select color', OM_CC_VC_DEVICE_TEXTDOMAIN),
					'value'      => array(
						esc_html__('Gold', OM_CC_VC_DEVICE_TEXTDOMAIN)       => 'om-cc-gold',
						esc_html__('Rose', OM_CC_VC_DEVICE_TEXTDOMAIN)       => 'om-cc-rose',
						esc_html__('Silver', OM_CC_VC_DEVICE_TEXTDOMAIN)     => 'om-cc-silver',
						esc_html__('Space Grey', OM_CC_VC_DEVICE_TEXTDOMAIN) => 'om-cc-grey',
					),
					'std'        => 'om-cc-gold',
					'dependency' => array(
						'element' => 'device_real_mockup',
						'value'   => 'om-cc-phone',
					),
				),
				array(
					'group' => $general,
					'param_name' => 'device_real_phone_landscape_color',
					'type'       => 'dropdown',
					'heading'    => esc_html__('Select color', OM_CC_VC_DEVICE_TEXTDOMAIN),
					'value'      => array(
						esc_html__('Gold', OM_CC_VC_DEVICE_TEXTDOMAIN)       => 'om-cc-gold',
						esc_html__('Rose', OM_CC_VC_DEVICE_TEXTDOMAIN)       => 'om-cc-rose',
						esc_html__('Silver', OM_CC_VC_DEVICE_TEXTDOMAIN)     => 'om-cc-silver',
						esc_html__('Space Grey', OM_CC_VC_DEVICE_TEXTDOMAIN) => 'om-cc-grey',
					),
					'std'        => 'om-cc-gold',
					'dependency' => array(
						'element' => 'device_real_mockup',
						'value'   => 'om-cc-phone-landscape',
					),
				),
				array(
					'group' => $general,
					'param_name' => 'device_real_tablet_color',
					'type'       => 'dropdown',
					'heading'    => esc_html__('Select color', OM_CC_VC_DEVICE_TEXTDOMAIN),
					'value'      => array(
						esc_html__('Black', OM_CC_VC_DEVICE_TEXTDOMAIN)  => 'om-cc-black',
						esc_html__('Gold', OM_CC_VC_DEVICE_TEXTDOMAIN)   => 'om-cc-gold',
						esc_html__('Silver', OM_CC_VC_DEVICE_TEXTDOMAIN) => 'om-cc-silver',
					),
					'std'        => 'om-cc-grey',
					'dependency' => array(
						'element' => 'device_real_mockup',
						'value'   => 'om-cc-tablet',
					),
				),
				array(
					'group' => $general,
					'param_name' => 'device_real_tablet_landscape_color',
					'type'       => 'dropdown',
					'heading'    => esc_html__('Select color', OM_CC_VC_DEVICE_TEXTDOMAIN),
					'value'      => array(
						esc_html__('Black', OM_CC_VC_DEVICE_TEXTDOMAIN)  => 'om-cc-black',
						esc_html__('Gold', OM_CC_VC_DEVICE_TEXTDOMAIN)   => 'om-cc-gold',
						esc_html__('Silver', OM_CC_VC_DEVICE_TEXTDOMAIN) => 'om-cc-silver',
					),
					'std'        => 'om-cc-grey',
					'dependency' => array(
						'element' => 'device_real_mockup',
						'value'   => 'om-cc-tablet-landscape',
					),
				),
				array(
					'group' => $general,
					'param_name' => 'device_real_watch_color',
					'type'       => 'dropdown',
					'heading'    => esc_html__('Select color', OM_CC_VC_DEVICE_TEXTDOMAIN),
					'value'      => array(
						esc_html__('Blue', OM_CC_VC_DEVICE_TEXTDOMAIN)  => 'om-cc-blue',
						esc_html__('Red', OM_CC_VC_DEVICE_TEXTDOMAIN)   => 'om-cc-red',
						esc_html__('Steel', OM_CC_VC_DEVICE_TEXTDOMAIN) => 'om-cc-steel',
						esc_html__('Green', OM_CC_VC_DEVICE_TEXTDOMAIN) => 'om-cc-green',
					),
					'std'        => 'om-cc-blue',
					'dependency' => array(
						'element' => 'device_real_mockup',
						'value'   => 'om-cc-watch',
					),
				),
				/*colorful*/
				array(
					'group' => $general,
					'param_name'  => 'device_colorful_mockup',
					'type'        => 'dropdown',
					'heading'     => esc_html__('Select mockup', OM_CC_VC_DEVICE_TEXTDOMAIN),
					'description' => esc_html__('Aspect ratio: Browser - 4/3, Phone - 16/9, Tablet - 4/3, Watch - 5/4', OM_CC_VC_DEVICE_TEXTDOMAIN),
					'value'       => array(
						esc_html__('Phone', OM_CC_VC_DEVICE_TEXTDOMAIN)            => 'om-cc-phone',
						esc_html__('Tablet', OM_CC_VC_DEVICE_TEXTDOMAIN)           => 'om-cc-tablet',
						esc_html__('Tablet landscape', OM_CC_VC_DEVICE_TEXTDOMAIN) => 'om-cc-tablet-landscape',
						esc_html__('Browser', OM_CC_VC_DEVICE_TEXTDOMAIN)          => 'om-cc-browser',
						esc_html__('Watch', OM_CC_VC_DEVICE_TEXTDOMAIN)            => 'om-cc-watch',
					),
					'std'         => 'om-cc-phone',
					'dependency'  => array(
						'element' => 'device_type',
						'value'   => 'om-cc-colorful',
					),
				),
				array(
					'group' => $general,
					'param_name' => 'device_color',
					'type'       => 'colorpicker',
					'heading'    => esc_html__('Select color', OM_CC_VC_DEVICE_TEXTDOMAIN),
					'dependency' => array(
						'element' => 'device_colorful_mockup',
						'value'   => array(
							'om-cc-phone',
							'om-cc-tablet',
							'om-cc-tablet-landscape',
							'om-cc-browser',
							'om-cc-watch'
						)
					),
				),
				array(
					'group' => $general,
					'param_name' => 'device_background_color',
					'type'       => 'colorpicker',
					'heading'    => esc_html__('Select background color', OM_CC_VC_DEVICE_TEXTDOMAIN),
					'dependency' => array(
						'element' => 'device_type',
						'value'   => array(
							'om-cc-flat',
							'om-cc-real'
						)
					),
				),
				array(
					'group' => $general,
					'param_name' => 'content_position_type',
					'type'       => 'dropdown',
					'heading'    => esc_html__('Content position', OM_CC_VC_DEVICE_TEXTDOMAIN),
					'value'      => array(
						esc_html__('Top', OM_CC_VC_DEVICE_TEXTDOMAIN)     => 'om-cc-top',
						esc_html__('Middle', OM_CC_VC_DEVICE_TEXTDOMAIN)  => 'om-cc-middle',
						esc_html__('Bottom', OM_CC_VC_DEVICE_TEXTDOMAIN)  => 'om-cc-bottom',
						esc_html__('Stretch', OM_CC_VC_DEVICE_TEXTDOMAIN) => 'om-cc-stretch',
					),
					'std'        => 'om-cc-top',
				),
				array(
					'group' => $general,
					'param_name' => 'content_animation_type',
					'type'       => 'dropdown',
					'heading'    => esc_html__('CSS Animation', OM_CC_VC_DEVICE_TEXTDOMAIN),
					'value'      => array(
						esc_html__('No', OM_CC_VC_DEVICE_TEXTDOMAIN)                 => '',
						esc_html__('Top to bottom', OM_CC_VC_DEVICE_TEXTDOMAIN)      => 'top-to-bottom',
						esc_html__('Bottom to top', OM_CC_VC_DEVICE_TEXTDOMAIN)      => 'bottom-to-top',
						esc_html__('Left to right', OM_CC_VC_DEVICE_TEXTDOMAIN)      => 'left-to-right',
						esc_html__('Right to left', OM_CC_VC_DEVICE_TEXTDOMAIN)      => 'right-to-left',
						esc_html__('Appear from center', OM_CC_VC_DEVICE_TEXTDOMAIN) => 'appear',
					),
					'std'        => '',
				),
				array(
					'group'      => esc_html__('Design', OM_CC_VC_DEVICE_TEXTDOMAIN),
					'type'       => 'css_editor',
					'heading'    => esc_html__('Css', OM_CC_VC_DEVICE_TEXTDOMAIN),
					'param_name' => 'css',
				),
			),
			'js_view'                 => 'VcColumnView'
		);
		
		$this->_makeContainer();
	}
	
	public function getDeviceVariants() {
		/** @var array[] $params */
		$params      = $this->parameters['params'];
		$devicesList = array();
		$deviceTypes = array('flat', 'real');
		
		foreach ($deviceTypes as $deviceType) {
			$devicesListItem = array(
				'name' => ucfirst($deviceType),
				'type' => 'om-cc-' . $deviceType
			);
			$devicesMockup   = array();
			foreach ($params as $param) {
				if ($param['param_name'] === 'device_' . $deviceType . '_mockup') {
					$devicesMockup = $param['value'];
					break;
				}
			}
			foreach ($devicesMockup as $key => $deviceMockup) {
				$devicesListItemMockup = array(
					'id'   => $deviceMockup,
					'name' => $key
				);
				foreach ($params as $param) {
					
					if (strpos($param['param_name'], $deviceType) === false) {
						continue;
					}
					
					$values = $param['dependency']['value'];
					if (!is_array($values)) {
						$values = array($values);
					}
					
					if (in_array($deviceMockup, $values, true)) {
						/** @var array $colors */
						$colors = $param['value'];
						foreach ($colors as $keyValue => $color) {
							$devicesListItemMockupColor        = array(
								'id'   => $color,
								'name' => $keyValue
							);
							$devicesListItemMockup['colors'][] = $devicesListItemMockupColor;
						}
					}
				}
				$devicesListItem['devices'][] = $devicesListItemMockup;
			}
			$devicesList[] = $devicesListItem;
		}
		
		return $devicesList;
	}
	
	public function _getStyles($hash, array $settings) {
		$styles = '';
		
		if (!empty($settings['device_color'])) {
			$styles .= $hash . ' .om-cc-device{background-color:' . $settings['device_color'] . '}';
		}
		if (!empty($settings['device_color']) && $settings['device_colorful_mockup'] === 'om-cc-watch') {
			$styles .= $hash . ' .om-cc-device.om-cc-colorful.om-cc-watch:before{background:' . $settings['device_color'] . '}';
		}
		if (!empty($settings['device_background_color']) && $settings['device_type'] !== 'om-cc-colorful') {
			$styles .= $hash . ' .om-cc-device>.om-cc-device-content-inner{background-color:' . $settings['device_background_color'] . '}';
		}
		
		return $styles;
	}
	
	protected function _getRenderedHtml() {
		$instance = $this->getFullSettings();
		$params   = $this->parameters['params'];
		//$deviceType = !empty($instance['device_type']) ? str_replace('_', ' ', $instance['device_type']) : 'om-cc-flat';
		
		$classes = array('om-cc-block', sanitize_html_class($this->hash));
		
		$css          = (true === array_key_exists('css', $instance)) ? $instance['css'] : '';
		$classes[]    = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($css, ' '), $this->tag, $instance);
		$deviceColor  = '';
		$classes[]    = $this->getCSSAnimation($instance['content_animation_type']);
		$deviceMockup = $instance['device_' . substr($instance['device_type'], 6) . '_mockup'];
		
		if ($deviceMockup !== 'om-cc-colorful') {
			foreach ($params as $param) {
				if (false !== strpos($param['param_name'],'color') && null !== $param['dependency']) {
					
					$element = $param['dependency']['element'];
					$values  = $param['dependency']['value'];
					
					if (is_array($values) && true === in_array($deviceMockup, $values) && false !== strpos($element, substr($instance['device_type'], 6))) {
						$deviceColor = (null !== $instance[$param['param_name']]) ? $instance[$param['param_name']] : '';
						break;
					} else {
						if ($values === $deviceMockup && false !== strpos($element, substr($instance['device_type'], 6))) {
							$deviceColor = (null !== $instance[$param['param_name']]) ? $instance[$param['param_name']] : '';
							break;
						}
					}
				}
			}
		} else {
			$deviceColor = 'device_colorful_color';
		}
		
		$deviceClasses = array(
			'om-cc-device',
			$instance['device_type'],
			$deviceMockup,
			$deviceColor,
			$instance['content_position_type']
		);
		
		$html = HtmlWriter::init()
		                  ->div(array('class' => $classes))
		                  ->div(array('class' => $deviceClasses))
		                  ->div(array('class' => 'om-cc-device-content-inner'))
		                  ->div(array('class' => 'om-cc-device-content-wrapper'))
		                  ->div(array('class' => 'om-cc-device-content'), wpb_js_remove_wpautop($this->content), true);
		
		return (string)$html;
	}
}
