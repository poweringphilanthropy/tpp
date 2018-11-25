<?php
/**
 * Plugin Name:     PP Toolkit - Philanthropy Overrides
 * Plugin URI:      http://www.poweringphilanthropy.com/
 * Description:     The Philanthropy Project overrides
 * Author:          Powering Philanthropy
 * Author URI:      http://www.poweringphilanthropy.com/
 * Author Email:    info@poweringphilanthropy.com
 * Version:         1.0
 * Text Domain:     pp-toolkit
 * Domain Path:     /languages/ 
 *
 * @package PP_Toolkit_Philanthropy_Overrides
 * @category Plugin
 * @author Lafif Astahdziq
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'PP_Toolkit_Philanthropy_Overrides' ) ) :

/**
 * Main PP_Toolkit_Philanthropy_Overrides Class
 *
 * @class PP_Toolkit_Philanthropy_Overrides
 * @version 1.0
 */
final class PP_Toolkit_Philanthropy_Overrides {

    /**
     * @var string
     */
    public $version = '1.0';

    public $capability = 'manage_options'; // default pp_toolkit_philanthropy capabilties

    public $plugin_domain = 'pp-toolkit';

    /**
     * @var PP_Toolkit_Philanthropy_Overrides The single instance of the class
     * @since 1.0
     */
    protected static $_instance = null;

    /**
     * Main PP_Toolkit_Philanthropy_Overrides Instance
     *
     * Ensures only one instance of PP_Toolkit_Philanthropy_Overrides is loaded or can be loaded.
     *
     * @since 1.0
     * @static
     * @return PP_Toolkit_Philanthropy_Overrides - Main instance
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * PP_Toolkit_Philanthropy_Overrides Constructor.
     */
    public function __construct() {

        $this->define_constants();
        $this->includes();
        $this->init_hooks();

        do_action( 'pp_toolkit_philanthropy_loaded' );
    }

    /**
     * Hook into actions and filters
     * @since  1.0
     */
    private function init_hooks() {
        add_action( 'init', array( $this, 'init' ), 0 );
    }

    /**
     * Init PP_Toolkit_Philanthropy_Overrides when WordPress Initialises.
     */
    public function init() {

        // init action
        do_action( 'pp_toolkit_philanthropy_init' );
    }

    /**
     * Define PPTPO Constants
     */
    private function define_constants() {
        global $wpdb;

        $this->define( 'PPTPO_PLUGIN_FILE', __FILE__ );
        $this->define( 'PPTPO_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
        $this->define( 'PPTPO_VERSION', $this->version );
    }

    /**
     * Define constant if not already set
     * @param  string $name
     * @param  string|bool $value
     */
    private function define( $name, $value ) {
        if ( ! defined( $name ) ) {
            define( $name, $value );
        }
    }

    /**
     * What type of request is this?
     * string $type ajax, frontend or admin
     * @return bool
     */
    public function is_request( $type ) {
        switch ( $type ) {
            case 'admin' :
                return is_admin();
            case 'ajax' :
                return defined( 'DOING_AJAX' );
            case 'cron' :
                return defined( 'DOING_CRON' );
            case 'frontend' :
                return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
        }
    }

    /**
     * Include required core files used in admin and on the frontend.
     */
    public function includes() {

        include_once( 'includes/pptpo-checkout.php' );
        include_once( 'includes/pptpo-user-profile.php' );

        if ( $this->is_request( 'admin' ) ) {

        }

        if ( $this->is_request( 'ajax' ) ) {
            // include_once( 'includes/ajax/..*.php' );
        }

        if ( $this->is_request( 'frontend' ) ) {
            // include_once( 'includes/class-funkmo_request.php' );
        }
    }

    public function load_default_admin_scripts(){

    }

    /**
     * Get the plugin url.
     * @return string
     */
    public function plugin_url() {
        return untrailingslashit( plugins_url( '/', __FILE__ ) );
    }

    /**
     * Get the plugin path.
     * @return string
     */
    public function plugin_path() {
        return untrailingslashit( plugin_dir_path( __FILE__ ) );
    }

    /**
     * Get Ajax URL.
     * @return string
     */
    public function ajax_url() {
        return admin_url( 'admin-ajax.php', 'relative' );
    }

}

endif;


/**
 * Returns the main instance of PP_Toolkit_Philanthropy_Overrides to prevent the need to use globals.
 *
 * @since  1.0
 * @return PP_Toolkit_Philanthropy_Overrides
 */
function PPTPO() {
    return PP_Toolkit_Philanthropy_Overrides::instance();
}

// echo PPTPO()->plugin_url();
$GLOBALS['pp_toolkit_philanthropy'] = PPTPO();