<?php
/**
 * PPTPO_User_Profile Class.
 *
 * @class       PPTPO_User_Profile
 * @version     1.0
 * @author lafif <hello@lafif.me>
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * PPTPO_User_Profile class.
 */
class PPTPO_User_Profile {

    /**
     * Singleton method
     *
     * @return self
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new PPTPO_User_Profile();
        }

        return $instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->includes();

        add_filter( 'charitable_user_fields', array($this, 'pp_charitable_user_fields'), 21, 2 );

    }

    public function pp_charitable_user_fields( $fields, $form ) {

        if(isset($fields[ 'organisation' ])){
            $fields[ 'organisation' ]['label'] = __('School/Organization', 'pp-toolkit');
        }

        if(isset($fields[ 'chapter' ])){
            unset($fields[ 'chapter' ]);
        }
        
          if(isset($fields[ 'description' ])){
            $fields[ 'description' ]['label'] = __('Who we are and why are we doing this?', 'pp-toolkit');
        }

        return $fields;
    }


    public function includes(){

    }

}

PPTPO_User_Profile::init();