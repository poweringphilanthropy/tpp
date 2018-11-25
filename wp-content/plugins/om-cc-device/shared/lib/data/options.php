<?php

namespace OM\CC\Shared\Lib\Data;

use OM\CC\Shared\Lib\System\Singleton;
use OM\CC\Shared\Lib\Html\HtmlWriter;

class Options extends Singleton {
	
	/**
	 * HTML of each option
	 * @var array
	 */
	private $_options;
	
	/**
	 * WP option group
	 * @var string
	 */
	private $_groupName = 'om-cc-plugins-settings-group';
	
	protected function __construct() {
		parent::__construct();
		
		add_action('init', array($this, '__register'));
	}
	
	/**
	 * @param string|callable $html
	 * @param string          $settingName
	 * @param int             $order
	 */
	public function addOption($html, $settingName = '', $order = 0) {
		$this->_options[] = array(
			'html'        => $html,
			'settingName' => 'om_cp_' . $settingName,
			'order'       => $order
		);
	}
	
	public function __register() {
		if (0 !== count($this->_options)) {
			add_action('admin_menu', array($this, '__onAdminMenu'));
			add_action('admin_init', array($this, '__onAdminInit'));
		}
	}
	
	public function __onAdminMenu() {
		add_options_page(
			esc_html__('Creative Plugins Settings', OM_CP_SHARED_TEXTDOMAIN),
			esc_html__('Creative Plugins', OM_CP_SHARED_TEXTDOMAIN),
			'manage_options',
			'creative-plugins-options',
			array($this, '__render')
		);
	}
	
	public function __onAdminInit() {
		$options = $this->_options;
		
		foreach ($options as $option) {
			register_setting($this->_groupName, $option['settingName']);
		}
	}
	
	public function __render() {
		ob_start();
		settings_fields($this->_groupName);
		$settingsFields = ob_get_contents();
		ob_end_clean();
		
		$html = HtmlWriter::init();
		$html->div(array('class' => 'wrap'))
		     ->h2(array(), 'Creative Plugins', true)
		     ->form(array('method' => 'post', 'action' => 'options.php'))
		     ->text($settingsFields)
		     ->table(array('class' => 'form-table'));
		
		usort($this->_options, function ($a, $b) {
			return ($a['order'] - $b['order']);
		});
		
		foreach ($this->_options as $option) {
			$html->tr(array('valign' => 'top'))
			     ->text(is_callable($option['html']) ? call_user_func($option['html']) : $option['html'])
			     ->end();
		}
		
		$html->end();
		
		$html->p(array('class' => 'submit'))
		     ->input(array(
			     'type'  => 'submit',
			     'class' => 'button-primary',
			     'value' => __('Save Changes', OM_CP_SHARED_TEXTDOMAIN)
		     ))
		     ->end(3);
		
		echo $html;
	}
	
	public static function get($option) {
		return get_option('om_cp_' . $option);
	}
}
