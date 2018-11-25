<?php
/**
 * PPTPO_Checkout Class.
 *
 * @class       PPTPO_Checkout
 * @version     1.0
 * @author lafif <hello@lafif.me>
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * PPTPO_Checkout class.
 */
class PPTPO_Checkout {

    /**
     * Singleton method
     *
     * @return self
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new PPTPO_Checkout();
        }

        return $instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->includes();

        add_action( 'philanthropy_project_start', array($this, 'on_pp_start'), 100 );

    }

    public function on_pp_start(){

        $pp_edd = PP_EDD::init();

        remove_action('edd_purchase_form_after_user_info', array($pp_edd, 'add_user_info') );
        remove_action('edd_register_fields_before', array($pp_edd, 'add_user_info') );
        remove_filter( 'edd_purchase_form_required_fields', array($pp_edd, 'require_user_info') );

        add_filter( 'edds_create_charge_args', array($this, 'change_edd_strpe_args'), 11, 2 );
    }

    public function change_edd_strpe_args($args, $purchase_data){

        if(isset($args['description'])){
            $args['description'] = 'PhilanthropyProject';
        }
        if(isset($args['statement_descriptor'])){
            $args['statement_descriptor'] = 'PhilanthropyProject';
        }

        return $args;
    }


    public function includes(){

    }

}

PPTPO_Checkout::init();