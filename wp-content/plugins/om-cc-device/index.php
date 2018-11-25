<?php

/**
 * @since             1.0.0
 * @package           Creative Devices Mock-ups
 * @wordpress-plugin
 * Plugin Name:       Creative Devices Mock-ups
 * Plugin URI:        http://openmarco.com/demo/creative-devices-mock-ups/
 * Description:       The most creative way to display content in devices mock-ups
 * Version:           1.3.0
 * Author:            Openmarco
 * Author URI:        http://openmarco.com/
 * License:           General Public License 2.0
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       om-cc-device
 * Domain Path:       /languages
 */

use OM\CC\Plugins\Device\Element;
use OM\CC\Shared\Lib\Utils\Utils;
use OM\CC\Shared\Universal\Features;
use OM\CC\Shared\Universal\Main;

define('OM_CC_VC_DEVICE_VERSION', '1.3.0');
define('OM_CC_VC_DEVICE_URL', plugins_url('/', __FILE__));
define('OM_CC_VC_DEVICE_PATH', plugin_dir_path(__FILE__));
define('OM_CC_VC_DEVICE_SHARED_VERSION', '1.3.0');
define('OM_CC_VC_DEVICE_TEXTDOMAIN', 'om-cc-device');

require_once __DIR__ . '/shared/functions.php';

om_cc_autoload(OM_CC_VC_DEVICE_SHARED_VERSION, OM_CC_VC_DEVICE_PATH, OM_CC_VC_DEVICE_URL, 'OM\CC\Plugins\Device');

add_action('plugins_loaded', function () {
	if (Utils::isVisualComposer()) {
		Main::init(Features::MODERNIZR);
		Element::create();
	}
	
	load_plugin_textdomain(OM_CC_VC_DEVICE_TEXTDOMAIN, false, OM_CC_VC_DEVICE_PATH . '/languages/');
}, 100);