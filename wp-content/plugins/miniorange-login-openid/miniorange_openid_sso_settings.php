<?php
/**
* Plugin Name: Social Login, Social Sharing by miniOrange
* Plugin URI: https://www.miniorange.com
* Description: Allow your users to login, comment and share with Facebook, Google, Twitter, LinkedIn etc using customizable buttons.
* Version: 7.0.4
* Author: miniOrange
* Author URI: https://www.miniorange.com
* License: GPL2
*/

require('miniorange_openid_sso_settings_page.php');
include_once dirname( __FILE__ ) . '/class-mo-openid-login-widget.php';
require('class-mo-openid-sso-customer.php');
require('class-mo-openid-sso-shortcode-buttons.php');
require('class-mo-openid-social-comment.php');
define('MO_OPENID_SOCIAL_LOGIN_VERSION', '7.0.4');
include dirname( __FILE__ ) . '/mo_openid_feedback_form.php';
class Miniorange_OpenID_SSO {

	function __construct() {

		add_action( 'admin_menu', array( $this, 'miniorange_openid_menu' ) );
		add_filter( 'plugin_action_links', array($this, 'mo_openid_plugin_actions'), 10, 2 );
		add_action( 'admin_init',  array( $this, 'miniorange_openid_save_settings' ) );

		add_action( 'plugins_loaded',  array( $this, 'mo_login_widget_text_domain' ) );
        add_action( 'plugins_loaded',  array( $this, 'mo_openid_plugin_update' ),1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'mo_openid_plugin_settings_style' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'mo_openid_plugin_settings_script' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'mo_openid_plugin_settings_style' ) ,5);
		add_action( 'wp_enqueue_scripts', array( $this, 'mo_openid_plugin_script' ) ,5);
        add_action( 'admin_footer', array( $this,'mo_openid_feedback_request' ));

		register_deactivation_hook(__FILE__, array( $this, 'mo_openid_deactivate'));
		register_activation_hook( __FILE__, array( $this, 'mo_openid_activate' ) );

		// add social login icons to default login form
		if(get_option('mo_openid_default_login_enable') == 1){
			add_action( 'login_form', array($this, 'mo_openid_add_social_login') );
			add_action( 'login_enqueue_scripts', array( $this, 'mo_custom_login_stylesheet' ) );
		}

		// add social login icons to default registration form
		if(get_option('mo_openid_default_register_enable') == 1){
			add_action( 'register_form', array($this, 'mo_openid_add_social_login') );
            add_action('login_enqueue_scripts', array( $this, 'mo_custom_login_stylesheet'));
		}

		//add shortcode
		add_shortcode( 'miniorange_social_login', array($this, 'mo_get_output') );
		add_shortcode( 'miniorange_social_sharing', array($this, 'mo_get_sharing_output') );
		add_shortcode( 'miniorange_social_sharing_vertical', array($this, 'mo_get_vertical_sharing_output') );
		add_shortcode( 'miniorange_social_comments', array($this, 'mo_get_comments_output') );

		// add social login icons to comment form
		if(get_option('mo_openid_default_comment_enable') == 1 ){
			add_action('comment_form_must_log_in_after', array($this, 'mo_openid_add_social_login'));
			add_action('comment_form_top', array($this, 'mo_openid_add_social_login'));
		}

		//add social login to woocommerce
		if(get_option('mo_openid_woocommerce_login_form') == 1){
			add_action( 'woocommerce_login_form_end', array($this, 'mo_openid_add_social_login'));
		}
        if(get_option('mo_openid_woocommerce_before_login_form') == 1){
            add_action( 'woocommerce_login_form_start', array($this, 'mo_openid_add_social_login'));
        }
        if(get_option('mo_openid_woocommerce_center_login_form') == 1){
            add_action( 'woocommerce_login_form', array($this, 'mo_openid_add_social_login'));
        }
        if(get_option('mo_openid_woocommerce_register_form_start') == 1){
            add_action( 'woocommerce_register_form_start', array($this, 'mo_openid_add_social_login'));
        }
        if(get_option('mo_openid_woocommerce_center_register_form') == 1){
            add_action( 'woocommerce_register_form', array($this, 'mo_openid_add_social_login'));
        }
        if(get_option('mo_openid_woocommerce_register_form_end') == 1){
            add_action( 'woocommerce_register_form_end', array($this, 'mo_openid_add_social_login'));
        }
        if(get_option('mo_openid_woocommerce_before_checkout_billing_form') == 1){
            add_action( 'woocommerce_before_checkout_billing_form', array($this, 'mo_openid_add_social_login'));
        }
        if(get_option('mo_openid_woocommerce_after_checkout_billing_form') == 1){
            add_action( 'woocommerce_after_checkout_billing_form', array($this, 'mo_openid_add_social_login'));
        }

/*        if(get_option('mo_openid_woocommerce_edit_account_form_start') == 1){
            add_action( 'woocommerce_edit_account_form_start', array($this, 'mo_openid_add_social_login'));
        }
        if(get_option('mo_openid_woocommerce_edit_account_form_end') == 1){
            add_action( 'woocommerce_edit_account_form_end', array($this, 'mo_openid_add_social_login'));
        }*/

        //add social login to buddypress
        if(get_option('mo_openid_bp_before_register_page') == 1){
            add_action( 'bp_before_register_page', array($this, 'mo_openid_add_social_login'));
        }
        if(get_option('mo_openid_bp_before_account_details_fields') == 1){
            add_action( 'bp_before_account_details_fields', array($this, 'mo_openid_add_social_login'));
        }
        if(get_option('mo_openid_bp_after_register_page') == 1){
            add_action( 'bp_after_register_page', array($this, 'mo_openid_add_social_login'));
        }

		if(get_option('mo_openid_logout_redirection_enable') == 0){
			remove_filter( 'logout_url', 'mo_openid_redirect_after_logout');
		}

		if(get_option('mo_share_options_wc_sp_summary') == 1){
			add_action('woocommerce_after_single_product_summary', array( $this, 'mo_openid_social_share' ));
		}

		if(get_option('mo_share_options_wc_sp_summary_top') == 1){
			add_action('woocommerce_single_product_summary', array( $this, 'mo_openid_social_share' ));
		}

		if(get_option('mo_openid_social_comment_fb') == 1 || get_option('mo_openid_social_comment_google') == 1 ){
			add_action('comment_form_top', array( $this, 'mo_openid_add_comment'));
		}

		if(get_option('mo_share_options_bb_forum') == 1){
			if(get_option('mo_share_options_bb_forum_position') == 'before')
				add_action('bbp_template_before_single_forum', array( $this, 'mo_openid_social_share' ));

			if(get_option('mo_share_options_bb_forum_position') == 'after')
				add_action('bbp_template_after_single_forum', array( $this, 'mo_openid_social_share' ));

			if(get_option('mo_share_options_bb_forum_position') == 'both'){
				add_action('bbp_template_before_single_forum', array( $this, 'mo_openid_social_share' ));
				add_action('bbp_template_after_single_forum', array( $this, 'mo_openid_social_share' ));
			}
		}

		if(get_option('mo_share_options_bb_topic') == 1){
			if(get_option('mo_share_options_bb_topic_position') == 'before')
				add_action('bbp_template_before_single_topic', array( $this, 'mo_openid_social_share' ));

			if(get_option('mo_share_options_bb_topic_position') == 'after')
				add_action('bbp_template_after_single_topic', array( $this, 'mo_openid_social_share' ));

			if(get_option('mo_share_options_bb_topic_position') == 'both'){
				add_action('bbp_template_before_single_topic', array( $this, 'mo_openid_social_share' ));
				add_action('bbp_template_after_single_topic', array( $this, 'mo_openid_social_share' ));
			}
		}

		if(get_option('mo_share_options_bb_reply') == 1){
			if(get_option('mo_share_options_bb_reply_position') == 'before')
				add_action('bbp_template_before_single_reply', array( $this, 'mo_openid_social_share' ));

			if(get_option('mo_share_options_bb_reply_position') == 'after')
				add_action('bbp_template_after_single_reply', array( $this, 'mo_openid_social_share' ));

			if(get_option('mo_share_options_bb_reply_position') == 'both'){
				add_action('bbp_template_before_single_reply', array( $this, 'mo_openid_social_share' ));
				add_action('bbp_template_after_single_reply', array( $this, 'mo_openid_social_share' ));
			}
		}

		add_filter( 'the_content', array( $this, 'mo_openid_add_social_share_links' ) );
		add_filter( 'the_excerpt', array( $this, 'mo_openid_add_social_share_links' ) );

		//custom avatar
		if(get_option('moopenid_social_login_avatar')) {
			add_filter( 'get_avatar', array( $this, 'mo_social_login_custom_avatar' ), 15, 5 );
			add_filter( 'get_avatar_url', array( $this, 'mo_social_login_custom_avatar_url' ), 15, 3);
			add_filter( 'bp_core_fetch_avatar', array( $this, 'mo_social_login_buddypress_avatar' ), 10, 2);
		}

		remove_action( 'admin_notices', array( $this, 'mo_openid_success_message') );
	    remove_action( 'admin_notices', array( $this, 'mo_openid_error_message') );

		//set default values
		add_option( 'mo_openid_login_redirect', 'same' );
		add_option( 'mo_openid_login_theme', 'longbutton' );
		add_option( 'mo_openid_oauth','0');
        add_option('mo_openid_new_user','0');
        add_option('mo_openid_malform_error','0');
		add_option( 'mo_openid_share_theme', 'oval' );
		add_option( 'mo_share_options_enable_post_position', 'before');
		add_option( 'mo_share_options_home_page_position', 'before');
		add_option( 'mo_share_options_static_pages_position', 'before');
		add_option( 'mo_share_options_bb_forum_position', 'before');
		add_option( 'mo_share_options_bb_topic_position', 'before');
		add_option( 'mo_share_options_bb_reply_position', 'before');
		add_option( 'mo_openid_default_login_enable', '1');
		add_option('mo_login_openid_login_widget_customize_textcolor','000000');
		add_option( 'mo_openid_login_widget_customize_text', 'Connect with:' );
		add_option( 'mo_openid_share_widget_customize_text', 'Share with:' );
		add_option( 'mo_openid_share_widget_customize_text_color', '000000');
		add_option( 'mo_openid_login_button_customize_text', 'Login with' );
		add_option( 'mo_openid_share_widget_customize_direction_horizontal','1' );
		add_option( 'mo_sharing_icon_custom_size','35' );
		add_option( 'mo_openid_share_custom_theme', 'default' );
		add_option( 'mo_sharing_icon_custom_color', '000000' );
		add_option( 'mo_sharing_icon_space', '4' );
		add_option( 'mo_sharing_icon_custom_font', '000000' );
		add_option( 'mo_login_icon_custom_size','35' );
		add_option( 'mo_login_icon_space','4' );
		add_option( 'mo_login_icon_custom_width','200' );
		add_option( 'mo_login_icon_custom_height','35' );
		add_option('mo_login_icon_custom_boundary','4');
		add_option( 'mo_openid_login_custom_theme', 'default' );
		add_option( 'mo_login_icon_custom_color', '2B41FF' );
		add_option( 'mo_openid_logout_redirection_enable', '0' );
		add_option( 'mo_openid_logout_redirect', 'currentpage' );
		add_option( 'mo_openid_auto_register_enable', '1');
		add_option( 'mo_openid_account_linking_enable', '0');
		add_option( 'mo_openid_email_enable', '1');
		add_option( 'mo_openid_register_disabled_message', 'Registration is disabled for this website. Please contact the administrator for any queries.' );
		add_option( 'mo_openid_register_email_message', 'Hello,<br><br>##User Name## has registered to your site  successfully.<br><br>Thanks,<br>miniOrange' );
		add_option( 'moopenid_social_login_avatar','1' );
		add_option( 'moopenid_user_attributes','0' );
		add_option( 'mo_share_vertical_hide_mobile', '1' );
		add_option( 'mo_openid_social_comment_blogpost','1' );
		add_option( 'mo_openid_social_comment_default_label', 'Default Comments' );
		add_option( 'mo_openid_social_comment_fb_label', 'Facebook Comments' );
		add_option( 'mo_openid_social_comment_google_label', 'Google+ Comments' );
		add_option( 'mo_openid_social_comment_disqus_label', 'Disqus Comments' );
		add_option( 'mo_openid_social_comment_heading_label', 'Leave a Reply' );
		add_option('mo_openid_login_role_mapping','subscriber');
		add_option( 'mo_openid_user_number',0);
		add_option( 'mo_openid_login_widget_customize_logout_name_text', 'Howdy, ##username## |' );
		add_option( 'mo_openid_login_widget_customize_logout_text', 'Logout?' );
		add_option( 'mo_openid_share_email_subject','I wanted you to see this site' );
		add_option( 'mo_openid_share_email_body','Check out this site ##url##' );
		add_option( 'mo_openid_enable_profile_completion','1' );
        add_option( 'moopenid_logo_check','1' );

        //profile completion
        add_option( 'mo_profile_complete_title','Profile Completion');
        add_option( 'mo_profile_complete_username_label','Username');
        add_option( 'mo_profile_complete_email_label','Email');
        add_option( 'mo_profile_complete_submit_button','Submit');
        add_option( 'mo_profile_complete_instruction','If you are an existing user on this site, enter your registered email and username. If you are a new user, please edit/fill the details.');
        add_option( 'mo_profile_complete_extra_instruction','We will be sending a verification code to this email to verify it. Please enter a valid email address.');
        add_option( 'mo_profile_complete_uname_exist','Entered username already exists. Try some other username');
        add_option( 'mo_email_verify_resend_otp_button','Resend OTP');
        add_option( 'mo_email_verify_back_button','Back');
        add_option( 'mo_email_verify_title','Verify your email');
        add_option( 'mo_email_verify_message','We have sent a verification code to given email. Please verify your account with it.');
        add_option( 'mo_email_verify_verification_code_instruction','Enter your verification code');
        add_option( 'mo_email_verify_wrong_otp','You have entered an invalid verification code. Enter a valid code.');




        //account linking
        add_option( 'mo_account_linking_title','Account Linking');
        add_option( 'mo_account_linking_new_user_button','Create a new account?');
        add_option( 'mo_account_linking_existing_user_button','Link to an existing account?');
        add_option( 'mo_account_linking_new_user_instruction','If you do not have an existing account with a different email address, click on <b>Create a new account</b>');
        add_option( 'mo_account_linking_existing_user_instruction','If you already have an existing account with a different email adddress and want to link this account with that, click on <b>Link to an existing account</b>.');
        add_option( 'mo_account_linking_extra_instruction','You will be redirected to login page to login to your existing account.');



        //woocommerce display options
        add_option( 'mo_openid_woocommerce_login_form','0');
        add_option( 'mo_openid_woocommerce_before_login_form','0');
        add_option( 'mo_openid_woocommerce_center_login_form','0');
        add_option( 'mo_openid_woocommerce_register_form_start','0');
        add_option( 'mo_openid_woocommerce_center_register_form','0');
        add_option( 'mo_openid_woocommerce_register_form_end','0');
        add_option( 'mo_openid_woocommerce_before_checkout_billing_form','0');
        add_option( 'mo_openid_woocommerce_after_checkout_billing_form','0');
        // add_option( 'mo_openid_woocommerce_edit_account_form_start');
        // add_option( 'mo_openid_woocommerce_edit_account_form_end');
        //buddypress display options
        add_option( 'mo_openid_bp_before_register_page','0');
        add_option( 'mo_openid_bp_before_account_details_fields','0');
        add_option( 'mo_openid_bp_after_register_page','0');

        //Custom app switch button option
        add_option('mo_openid_enable_custom_app_google','1');
        add_option('mo_openid_enable_custom_app_facebook','1');
        add_option('mo_openid_enable_custom_app_twitter','1');

        //GDPR options
        add_option('mo_openid_gdpr_consent_enable', 0);
        add_option( 'mo_openid_privacy_policy_text', 'terms and conditions');
        add_option( 'mo_openid_gdpr_consent_message','I accept the terms and conditions.');
        //Error messages option
        add_option( 'mo_registration_error_message','There was an error in registration. Please contact your administrator.');
        add_option( 'mo_email_failure_message','Either your SMTP is not configured or you have entered an unmailable email. Please go back and try again.');
        add_option( 'mo_existing_username_error_message','This username already exists. Please ask the administrator to create your account with a unique username.');
        add_option( 'mo_manual_login_error_message','There was an error during login. Please try to login/register manually. <a href='.site_url().'>Go back to site</a>');
        add_option( 'mo_delete_user_error_message','Error deleting user from account linking table');
        add_option( 'mo_account_linking_message','Link your social account to existing WordPress account by entering username and password.');

    }

    function mo_openid_feedback_request(){
        mo_openid_display_feedback_form();
    }

	function mo_openid_deactivate() {
		delete_option('mo_openid_host_name');
		delete_option('mo_openid_transactionId');
		delete_option('mo_openid_admin_password');
		delete_option('mo_openid_registration_status');
		delete_option('mo_openid_admin_phone');
		delete_option('mo_openid_new_registration');
		delete_option('mo_openid_admin_customer_key');
		delete_option('mo_openid_admin_api_key');
		delete_option('mo_openid_customer_token');
		delete_option('mo_openid_verify_customer');
		delete_option('mo_openid_message');
		delete_option( 'mo_openid_admin_customer_valid');
		delete_option( 'mo_openid_admin_customer_plan');
	}

    //  create mo_openid_linked_user if it doesn't exist
    // + add entries in wp_mo_openid_linked_user table
    // + remove columns app name and identifier from wp_users table
	function mo_openid_plugin_update(){
        global $wpdb;
        $table_name = $wpdb->prefix . 'mo_openid_linked_user';
        $charset_collate = $wpdb->get_charset_collate();

        $time = $wpdb->get_var("SELECT COLUMN_NAME 
                                    FROM information_schema.COLUMNS 
                                    WHERE
                                     TABLE_SCHEMA='$wpdb->dbname'
                                     AND COLUMN_NAME = 'timestamp'");

        // if table mo_openid_linked_user doesn't exist or the 'timestamp' column doesn't exist
        if($wpdb->get_var("show tables like '$table_name'") != $table_name || empty($time) ) {
            $sql = "CREATE TABLE $table_name (
                    id mediumint(9) NOT NULL AUTO_INCREMENT,
                    linked_social_app varchar(55) NOT NULL,
                    linked_email varchar(55) NOT NULL,
                    user_id mediumint(10) NOT NULL,
                    identifier VARCHAR(100) NOT NULL,
                    timestamp datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                    PRIMARY KEY  (id)
                ) $charset_collate;";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);

            $identifier = $wpdb->get_var("SELECT COLUMN_NAME 
                                    FROM information_schema.COLUMNS 
                                    WHERE 
                                    TABLE_NAME = '$wpdb->users' 
                                    AND TABLE_SCHEMA='$wpdb->dbname'
                                    AND COLUMN_NAME = 'identifier'");

            if(strcasecmp( $identifier, "identifier") == 0 ){

                $count= $wpdb->get_var("SELECT count(ID) FROM $wpdb->users WHERE identifier not LIKE ''");
                $result= $wpdb->get_results("SELECT * FROM $wpdb->users WHERE identifier not LIKE ''");

                for($icnt = 0; $icnt < $count; $icnt = $icnt + 1){

                    $provider = $result[$icnt]->provider;
                    $split_app_name = explode('_', $provider);
                    $provider = strtolower($split_app_name[0]);
                    $user_email = $result[$icnt]->user_email;
                    $ID = $result[$icnt]->ID;
                    $identifier = $result[$icnt]->identifier;

                    $output = $wpdb->insert(
                        $table_name,
                        array(
                            'linked_social_app' => $provider,
                            'linked_email' => $user_email,
                            'user_id' =>  $ID,
                            'identifier' => $identifier
                        ),
                        array(
                            '%s',
                            '%s',
                            '%d',
                            '%s'
                        )
                    );
                    if($output === false){
                        /*$wpdb->show_errors();
                        $wpdb->print_error();
                        exit;*/
                        wp_die('Error in insert Query');
                    }

                }
                $wpdb->get_var("ALTER TABLE $wpdb->users DROP COLUMN provider");
                $wpdb->get_var("ALTER TABLE $wpdb->users DROP COLUMN identifier");
            }
        }

        $current_version = get_option('mo_openid_social_login_version');

        if(!$current_version && version_compare(MO_OPENID_SOCIAL_LOGIN_VERSION,"6.6.1",">=")){
            //delete entries from mo_openid_linked_user table which have empty column values
            $result = $wpdb->query(
                $wpdb->prepare(
                    "
                        DELETE FROM {$table_name}
                        WHERE linked_social_app = %s
                        OR linked_email = %s
                        OR user_id = %d
                        OR identifier = %s
                        ",
                    '','',0, ''
                )
            );
            if($result === false){
                /*$wpdb->show_errors();
                $wpdb->print_error();
                exit;*/
                wp_die('Error in deletion query');
            }
        }
        update_option('mo_openid_social_login_version',MO_OPENID_SOCIAL_LOGIN_VERSION);
    }

	function mo_openid_activate() {
		add_option('Activated_Plugin','Plugin-Slug');
        update_option( 'mo_openid_host_name', 'https://auth.miniorange.com' );
	}

	function mo_openid_add_social_login(){

		if(!is_user_logged_in() && mo_openid_is_customer_registered() && strpos( $_SERVER['QUERY_STRING'], 'disable-social-login' ) == false){
			$mo_login_widget = new mo_openid_login_wid();
			$mo_login_widget->openidloginForm();
		}
	}

	function mo_openid_add_social_share_links($content) {
		global $post;
		$post_content=$content;
		$title = str_replace('+', '%20', urlencode($post->post_title));

		if(is_front_page() && get_option('mo_share_options_enable_home_page')==1){
			$html_content = mo_openid_share_shortcode('', $title);

			if ( get_option('mo_share_options_home_page_position') == 'before' ) {
				return  $html_content . $post_content;
			} else if ( get_option('mo_share_options_home_page_position') == 'after' ) {
				 return   $post_content . $html_content;
			} else if ( get_option('mo_share_options_home_page_position') == 'both' ) {
				 return $html_content . $post_content . $html_content;
			}
		} else if(is_page() && get_option('mo_share_options_enable_static_pages')==1){
			$html_content = mo_openid_share_shortcode('', $title);

			if ( get_option('mo_share_options_static_pages_position') == 'before' ) {
				return  $html_content . $post_content;
			} else if ( get_option('mo_share_options_static_pages_position') == 'after' ) {
				 return   $post_content . $html_content;
			} else if ( get_option('mo_share_options_static_pages_position') == 'both' ) {
				 return $html_content . $post_content . $html_content;
			}
		} else if(is_single() && get_option('mo_share_options_enable_post') == 1 ){
			$html_content = mo_openid_share_shortcode('', $title);

			if ( get_option('mo_share_options_enable_post_position') == 'before' ) {
				return  $html_content . $post_content;
			} else if ( get_option('mo_share_options_enable_post_position') == 'after' ) {
				 return   $post_content . $html_content;
			} else if ( get_option('mo_share_options_enable_post_position') == 'both' ) {
				 return $html_content . $post_content . $html_content;
			}
		} else
			return $post_content;

	}

	function mo_openid_social_share(){
		global $post;
		$title = str_replace('+', '%20', urlencode($post->post_title));
		echo mo_openid_share_shortcode('', $title);
	}

	function mo_openid_add_comment(){
		global $post;
		if(isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'){
			$http = "https://";
		} else {
			$http = "http://";
		}
		$url = $http . $_SERVER["HTTP_HOST"] . $_SERVER['REQUEST_URI'];
		if(is_single() && get_option('mo_openid_social_comment_blogpost') == 1 ) {
			mo_openid_social_comment($post, $url);
		} else if(is_page() && get_option('mo_openid_social_comment_static')==1) {
			mo_openid_social_comment($post, $url);
		}
	}

	function mo_custom_login_stylesheet()
	{
		wp_enqueue_style( 'mo-wp-style',plugins_url('includes/css/mo_openid_style.css?version=5.0.6', __FILE__), false );
		wp_enqueue_style( 'mo-wp-bootstrap-social',plugins_url('includes/css/bootstrap-social.css', __FILE__), false );
		wp_enqueue_style( 'mo-wp-bootstrap-main',plugins_url('includes/css/bootstrap.min.css', __FILE__), false );
		wp_enqueue_style( 'mo-wp-font-awesome',plugins_url('includes/css/font-awesome.min.css?version=4.8', __FILE__), false );
		wp_enqueue_style( 'mo-wp-font-awesome',plugins_url('includes/css/font-awesome.css?version=4.8', __FILE__), false );
	}

	function mo_openid_plugin_settings_style() {
		wp_enqueue_style( 'mo_openid_admin_settings_style', plugins_url('includes/css/mo_openid_style.css?version=5.0.6', __FILE__));
		wp_enqueue_style( 'mo_openid_admin_settings_phone_style', plugins_url('includes/css/phone.css', __FILE__));
		wp_enqueue_style( 'mo-wp-bootstrap-social',plugins_url('includes/css/bootstrap-social.css', __FILE__), false );
		wp_enqueue_style( 'mo-wp-bootstrap-main',plugins_url('includes/css/bootstrap.min-preview.css', __FILE__), false );
		wp_enqueue_style( 'mo-wp-font-awesome',plugins_url('includes/css/font-awesome.min.css?version=4.8', __FILE__), false );
		wp_enqueue_style( 'mo-wp-font-awesome',plugins_url('includes/css/font-awesome.css?version=4.8', __FILE__), false );

	}

	function mo_openid_plugin_script() {
		wp_enqueue_script( 'js-cookie-script',plugins_url('includes/js/jquery.cookie.min.js', __FILE__), array('jquery'));
		wp_enqueue_script( 'mo-social-login-script',plugins_url('includes/js/social_login.js', __FILE__), array('jquery') );
	}

	function mo_openid_plugin_settings_script() {
		wp_enqueue_script( 'mo_openid_admin_settings_phone_script', plugins_url('includes/js/phone.js', __FILE__ ));
		wp_enqueue_script( 'mo_openid_admin_settings_color_script', plugins_url('includes/jscolor/jscolor.js', __FILE__ ));
		wp_enqueue_script( 'mo_openid_admin_settings_script', plugins_url('includes/js/settings.js?version=4.9.6', __FILE__ ), array('jquery'));
		wp_enqueue_script( 'mo_openid_admin_settings_phone_script', plugins_url('includes/js/bootstrap.min.js', __FILE__ ));
	}

	function mo_openid_success_message() {
		$message = get_option('mo_openid_message'); ?>
		<script>

		jQuery(document).ready(function() {
			var message = "<?php echo $message; ?>";
			jQuery('#mo_openid_msgs').append("<div class='error notice is-dismissible mo_openid_error_container'> <p class='mo_openid_msgs'>" + message + "</p></div>");
		});
		</script>
	<?php }

	function mo_openid_error_message() {
		$message = get_option('mo_openid_message'); ?>
		<script>
		jQuery(document).ready(function() {
			var message = "<?php echo $message; ?>";
			jQuery('#mo_openid_msgs').append("<div class='updated notice is-dismissible mo_openid_success_container'> <p class='mo_openid_msgs'>" + message + "</p></div>");
		});
		</script>
	<?php }

	private function mo_openid_show_success_message() {
		remove_action( 'admin_notices', array( $this, 'mo_openid_success_message') );
		add_action( 'admin_notices', array( $this, 'mo_openid_error_message') );
	}

	private function mo_openid_show_error_message() {
		remove_action( 'admin_notices', array( $this, 'mo_openid_error_message') );
		add_action( 'admin_notices', array( $this, 'mo_openid_success_message') );
	}
    function mo_openid_success_facebook_message() {
        $message = 'Other setting are saved sucessfully. For Facebook please setup the Facebook Custome App. '; ?>
        <script>

            jQuery(document).ready(function() {
                var message = "<?php echo $message; ?>";
                jQuery('#mo_openid_msgs').append("<div class='error notice is-dismissible mo_openid_error_container'> <p class='mo_openid_msgs'>" + message + "</p></div>");
            });
        </script>
    <?php }

    private function mo_openid_show_facebook_error_message() {
        remove_action( 'admin_notices', array( $this, 'mo_openid_error_message') );
        add_action( 'admin_notices', array( $this, 'mo_openid_success_facebook_message') );
    }

	public function mo_openid_check_empty_or_null( $value ) {
		if( ! isset( $value ) || empty( $value ) ) {
			return true;
		}
		return false;
	}

	function  mo_login_widget_openid_options() {
        update_option( 'mo_openid_host_name', 'https://auth.miniorange.com' );
		mo_register_openid();
	}

	function mo_openid_activation_message() {
		$class = "updated";
		$message = get_option('mo_openid_message');
		echo "<div class='" . $class . "'> <p>" . $message . "</p></div>";
	}

	function mo_login_widget_text_domain(){
		load_plugin_textdomain('flw', FALSE, basename( dirname( __FILE__ ) ) .'/languages');
	}

	public function mo_oauth_check_empty_or_null( $value ) {
		if( ! isset( $value ) || empty( $value ) ) {
			return true;
		}
		return false;
	}

    public function if_custom_app_exists($app_name){
        if(get_option('mo_openid_apps_list'))
            $appslist = get_option('mo_openid_apps_list');
        else
            $appslist = array();

        foreach( $appslist as $key => $app){
            $option = 'mo_openid_enable_custom_app_' . $key;
            if($app_name == $key && get_option($option) == '1')
                return true;
        }
        return false;
    }

	function miniorange_openid_save_settings(){

		if ( current_user_can( 'manage_options' )){
		if(is_admin() && get_option('Activated_Plugin')=='Plugin-Slug') {

			delete_option('Activated_Plugin');
			update_option('mo_openid_message','Go to plugin <b><a href="admin.php?page=mo_openid_settings">settings</a></b> to enable Social Login, Social Sharing by miniOrange.');
			add_action('admin_notices', array($this, 'mo_openid_activation_message'));
		}

		if( isset( $_POST['option'] ) and $_POST['option'] == "mo_openid_connect_register_customer" ) {	//register the admin to miniOrange

			//validation and sanitization
			$company = '';
			$first_name = '';
			$last_name = '';
			$email = '';
			$phone = '';
			$password = '';
			$confirmPassword = '';
			$illegal = "#$%^*()+=[]';,/{}|:<>?~";
			$illegal = $illegal . '"';
			if( $this->mo_openid_check_empty_or_null( $_POST['company'] ) || $this->mo_openid_check_empty_or_null( $_POST['email'] ) || $this->mo_openid_check_empty_or_null( $_POST['password'] ) || $this->mo_openid_check_empty_or_null( $_POST['confirmPassword'] ) ) {
				update_option( 'mo_openid_message', 'All the fields are required. Please enter valid entries.');
				$this->mo_openid_show_error_message();
				return;
			} else if( strlen( $_POST['password'] ) < 6 || strlen( $_POST['confirmPassword'] ) < 6){	//check password is of minimum length 6
				update_option( 'mo_openid_message', 'Choose a password with minimum length 6.');
				$this->mo_openid_show_error_message();
				return;
			} else if(strpbrk($_POST['email'],$illegal)) {
				update_option( 'mo_openid_message', 'Please match the format of Email. No special characters are allowed.');
				$this->mo_openid_show_error_message();
				return;
			} else {
				$company = sanitize_text_field($_POST['company']);
				$first_name = sanitize_text_field(isset($_POST['fname'])?$_POST['fname']:'');
				$last_name = sanitize_text_field(isset($_POST['lname'])?$_POST['lname']:'');
				$email = sanitize_email( $_POST['email'] );
				$phone = sanitize_text_field( isset($_POST['phone'])?$_POST['phone']:'' );
				$password = sanitize_text_field( $_POST['password'] );
				$confirmPassword = sanitize_text_field( $_POST['confirmPassword'] );
			}

			update_option( 'mo_openid_admin_company_name', $company);
			update_option( 'mo_openid_admin_first_name', $first_name);
			update_option( 'mo_openid_admin_last_name', $last_name);
			update_option( 'mo_openid_admin_email', $email );
			update_option( 'mo_openid_admin_phone', $phone );

			if( strcmp( $password, $confirmPassword) == 0 ) {
				update_option( 'mo_openid_admin_password', $password );

				$customer = new CustomerOpenID();
				$content = json_decode($customer->check_customer(), true);
				if( strcasecmp( $content['status'], 'CUSTOMER_NOT_FOUND') == 0 ){
					$content = json_decode($customer->send_otp_token('EMAIL'), true);
					if(strcasecmp($content['status'], 'SUCCESS') == 0) {
						if(get_option('mo_openid_email_otp_count')){
							update_option('mo_openid_email_otp_count',get_option('mo_openid_email_otp_count') + 1);
							update_option('mo_openid_message', 'Another One Time Passcode has been sent <b>( ' . get_option('mo_openid_email_otp_count') . ' )</b> for verification to ' . get_option('mo_openid_admin_email'));
						}else{
							update_option( 'mo_openid_message', ' A passcode is sent to ' . get_option('mo_openid_admin_email') . '. Please enter the otp here to verify your email.');
							update_option('mo_openid_email_otp_count',1);
						}
						update_option('mo_openid_transactionId',$content['txId']);
						update_option('mo_openid_registration_status','MO_OTP_DELIVERED_SUCCESS');

						$this->mo_openid_show_success_message();
					}else{
						update_option('mo_openid_message','There was an error in sending email. Please click on Resend OTP to try again.');
						update_option('mo_openid_registration_status','MO_OTP_DELIVERED_FAILURE');
						$this->mo_openid_show_error_message();
					}
				}else{
						$this->get_current_customer();
				}

			} else {
				update_option( 'mo_openid_message', 'Passwords do not match.');
				delete_option('mo_openid_verify_customer');
				$this->mo_openid_show_error_message();
			}

		}
		else if(isset($_POST['option']) and $_POST['option'] == "mo_openid_validate_otp"){

			//validation and sanitization
			$otp_token = '';
			if( $this->mo_openid_check_empty_or_null( $_POST['otp_token'] ) ) {
				update_option( 'mo_openid_message', 'Please enter a value in OTP field.');
				update_option('mo_openid_registration_status','MO_OTP_VALIDATION_FAILURE');
				$this->mo_openid_show_error_message();
				return;
			} else if(!preg_match('/^[0-9]*$/', $_POST['otp_token'])) {
				update_option( 'mo_openid_message', 'Please enter a valid value in OTP field.');
				update_option('mo_openid_registration_status','MO_OTP_VALIDATION_FAILURE');
				$this->mo_openid_show_error_message();
				return;
			} else{
				$otp_token = sanitize_text_field( $_POST['otp_token'] );
			}

			$customer = new CustomerOpenID();
			$content = json_decode($customer->validate_otp_token(get_option('mo_openid_transactionId'), $otp_token ),true);
			if(strcasecmp($content['status'], 'SUCCESS') == 0) {
				$this->create_customer();
                update_option('mo_openid_oauth','1');
                update_option('mo_openid_new_user','1');
                update_option('mo_openid_malform_error','1');


            }else{
				update_option( 'mo_openid_message','Invalid one time passcode. Please enter a valid passcode.');
				update_option('mo_openid_registration_status','MO_OTP_VALIDATION_FAILURE');
				$this->mo_openid_show_error_message();
			}
		}
		else if( isset($_POST['option']) and $_POST['option'] == 'mo_openid_phone_verification'){ //at registration time
			$phone = sanitize_text_field($_POST['phone_number']);

			$phone = str_replace(' ', '', $phone);
			update_option('mo_openid_admin_phone',$phone);
			$auth_type = 'SMS';
			$customer = new CustomerOpenID();
			$send_otp_response = json_decode($customer->send_otp_token($auth_type),true);
			if(strcasecmp($send_otp_response['status'], 'SUCCESS') == 0){
				//Save txId

				update_option('mo_openid_transactionId',$send_otp_response['txId']);
				update_option( 'mo_openid_registration_status','MO_OTP_DELIVERED_SUCCESS');
				if(get_option('mo_openid_sms_otp_count')){
					update_option('mo_openid_sms_otp_count',get_option('mo_openid_sms_otp_count') + 1);
					update_option('mo_openid_message', 'Another One Time Passcode has been sent <b>( ' . get_option('mo_openid_sms_otp_count') . ' )</b> for verification to ' . $phone);
				}else{

						update_option('mo_openid_message', 'One Time Passcode has been sent for verification to ' . $phone);
						update_option('mo_openid_sms_otp_count',1);
				}

				$this->mo_openid_show_success_message();

			}else{
				update_option('mo_openid_message','There was an error in sending sms. Please click on Resend OTP link next to phone number textbox.');
				update_option('mo_openid_registration_status','MO_OTP_DELIVERED_FAILURE');
				$this->mo_openid_show_error_message();

			}
		}
        else if( isset( $_POST['option'] ) and $_POST['option'] == "mo_openid_connect_verify_customer" ) {	//register the admin to miniOrange

			//validation and sanitization
			$email = '';
			$password = '';
			$illegal = "#$%^*()+=[]';,/{}|:<>?~";
			$illegal = $illegal . '"';
			if( $this->mo_openid_check_empty_or_null( $_POST['email'] ) || $this->mo_openid_check_empty_or_null( $_POST['password'] ) ) {
				update_option( 'mo_openid_message', 'All the fields are required. Please enter valid entries.');
				$this->mo_openid_show_error_message();
				return;
			} else if(strpbrk($_POST['email'],$illegal)) {
				update_option( 'mo_openid_message', 'Please match the format of Email. No special characters are allowed.');
				$this->mo_openid_show_error_message();
				return;
			} else{
				$email = sanitize_email( $_POST['email'] );
				$password = sanitize_text_field( $_POST['password'] );
			}

			update_option( 'mo_openid_admin_email', $email );
			update_option( 'mo_openid_admin_password', $password );
			$customer = new CustomerOpenID();
			$content = $customer->get_customer_key();
			$customerKey = json_decode( $content, true );
			if( isset($customerKey) ) {
				update_option( 'mo_openid_admin_customer_key', $customerKey['id'] );
				update_option( 'mo_openid_admin_api_key', $customerKey['apiKey'] );
				update_option( 'mo_openid_customer_token', $customerKey['token'] );
				update_option( 'mo_openid_admin_phone', $customerKey['phone'] );
				update_option('mo_openid_admin_password', '');
				update_option( 'mo_openid_message', 'Your account has been retrieved successfully.');
				delete_option('mo_openid_verify_customer');
				$this->mo_openid_show_success_message();

			} else {
				update_option( 'mo_openid_message', 'Invalid username or password. Please try again.');
				$this->mo_openid_show_error_message();
			}
			update_option('mo_openid_admin_password', '');
		}
		else if(isset($_POST['option']) and $_POST['option'] == 'mo_openid_forgot_password'){
			$email = get_option('mo_openid_admin_email');
			if( $this->mo_openid_check_empty_or_null( $email ) ) {
				if( $this->mo_openid_check_empty_or_null( $_POST['email'] ) ) {
					update_option( 'mo_openid_message', 'No email provided. Please enter your email below to reset password.');
					$this->mo_openid_show_error_message();
					return;
				} else {
					$email = $_POST['email'];
				}
			}
			$customer = new CustomerOpenID();
			$content = json_decode($customer->forgot_password($email),true);
			if(strcasecmp($content['status'], 'SUCCESS') == 0){
				update_option( 'mo_openid_message','You password has been reset successfully. Please enter the new password sent to your registered mail here.');
				$this->mo_openid_show_success_message();
			}else{
				update_option( 'mo_openid_message','An error occured while processing your request. Please make sure you are registered with miniOrange with the given email address.');
				$this->mo_openid_show_error_message();
			}
		}
		else if(isset($_POST['option']) and $_POST['option'] == 'mo_openid_check_license'){
			if(mo_openid_is_customer_registered()) {
				$customer = new CustomerOpenID();
				$content = json_decode($customer->check_customer_valid(),true);
				if(strcasecmp($content['status'], 'SUCCESS') == 0){
					update_option( 'mo_openid_admin_customer_valid', strcasecmp($content['licenseType'], 'Premium') !== FALSE ? 1 : 0);
					update_option( 'mo_openid_admin_customer_plan', isset($content['licensePlan']) ? base64_encode($content['licensePlan']) : 0);
					if(get_option('mo_openid_admin_customer_valid') && isset($content['licensePlan'])){
						$license = explode(' -', $content['licensePlan']);
						$lp = $license[0];
						update_option( 'mo_openid_message','You are on the old ' . $lp . '.');
					} else
						update_option( 'mo_openid_message','You are on the Free Plan.');
					$this->mo_openid_show_success_message();
				}else if(strcasecmp($content['status'], 'FAILED') == 0){
					update_option('mo_openid_message', 'You are on Free Plan.');
					$this->mo_openid_show_success_message();
				}else{
					update_option( 'mo_openid_message','An error occured while processing your request. Please try again.');
					$this->mo_openid_show_error_message();
				}
			} else {
				update_option('mo_openid_message', 'Please register an account before trying to check your plan');
				$this->mo_openid_show_error_message();
			}
		}
		else if( isset( $_POST['option'] ) and $_POST['option'] == "mo_openid_enable_apps" ) {


				update_option( 'mo_openid_google_enable', isset( $_POST['mo_openid_google_enable']) ? $_POST['mo_openid_google_enable'] : 0);
				update_option( 'mo_openid_salesforce_enable', isset( $_POST['mo_openid_salesforce_enable']) ? $_POST['mo_openid_salesforce_enable'] : 0);
                if($this->if_custom_app_exists('facebook')) {
                    update_option('mo_openid_facebook_enable', isset($_POST['mo_openid_facebook_enable']) ? $_POST['mo_openid_facebook_enable'] : 0);
                }
                else if(isset($_POST['mo_openid_facebook_enable'])) {
                    update_option('mo_openid_facebook_enable',0);
                    $this->mo_openid_show_facebook_error_message();

                }
                update_option( 'mo_openid_linkedin_enable', isset( $_POST['mo_openid_linkedin_enable']) ? $_POST['mo_openid_linkedin_enable'] : 0);
				update_option( 'mo_openid_windowslive_enable', isset( $_POST['mo_openid_windowslive_enable']) ? $_POST['mo_openid_windowslive_enable'] : 0);
				update_option( 'mo_openid_amazon_enable', isset( $_POST['mo_openid_amazon_enable']) ? $_POST['mo_openid_amazon_enable'] : 0);
				update_option( 'mo_openid_instagram_enable', isset( $_POST['mo_openid_instagram_enable']) ? $_POST['mo_openid_instagram_enable'] : 0);
				update_option( 'mo_openid_twitter_enable', isset( $_POST['mo_openid_twitter_enable']) ? $_POST['mo_openid_twitter_enable'] : 0);
				update_option( 'mo_openid_vkontakte_enable', isset( $_POST['mo_openid_vkontakte_enable']) ? $_POST['mo_openid_vkontakte_enable'] : 0);

				update_option( 'mo_openid_default_login_enable', isset( $_POST['mo_openid_default_login_enable']) ? $_POST['mo_openid_default_login_enable'] : 0);
			    update_option( 'mo_openid_default_register_enable', isset( $_POST['mo_openid_default_register_enable']) ? $_POST['mo_openid_default_register_enable'] : 0);
			    update_option( 'mo_openid_default_comment_enable', isset( $_POST['mo_openid_default_comment_enable']) ? $_POST['mo_openid_default_comment_enable'] : 0);



                // GDPR options
                update_option( 'mo_openid_gdpr_consent_enable', isset( $_POST['mo_openid_gdpr_consent_enable']) ? $_POST['mo_openid_gdpr_consent_enable'] : 0);
                if(get_option('mo_openid_gdpr_consent_enable') == 1 && (!mo_openid_restrict_user())) {
                    update_option('mo_openid_privacy_policy_url', isset($_POST['mo_openid_privacy_policy_url']) ? $_POST['mo_openid_privacy_policy_url'] : get_option('mo_openid_privacy_policy_url'));
                    update_option('mo_openid_privacy_policy_text', isset($_POST['mo_openid_privacy_policy_text']) ? $_POST['mo_openid_privacy_policy_text'] : get_option('mo_openid_privacy_policy_text'));
                    update_option('mo_openid_gdpr_consent_message', isset($_POST['mo_openid_gdpr_consent_message']) ? stripslashes($_POST['mo_openid_gdpr_consent_message']) : get_option('mo_openid_gdpr_consent_message'));
                }
                //Redirect URL
				update_option( 'mo_openid_login_redirect', $_POST['mo_openid_login_redirect']);
				update_option( 'mo_openid_login_redirect_url', $_POST['mo_openid_login_redirect_url'] );
                update_option( 'mo_openid_relative_login_redirect_url', isset( $_POST['mo_openid_relative_login_redirect_url']) ? $_POST['mo_openid_relative_login_redirect_url'] : "" );

				//Logout Url
				update_option( 'mo_openid_logout_redirection_enable', isset( $_POST['mo_openid_logout_redirection_enable']) ? $_POST['mo_openid_logout_redirection_enable'] : 0);
				update_option( 'mo_openid_logout_redirect', $_POST['mo_openid_logout_redirect']);
				update_option( 'mo_openid_logout_redirect_url', $_POST['mo_openid_logout_redirect_url'] );

				//auto register
				update_option( 'mo_openid_auto_register_enable', isset( $_POST['mo_openid_auto_register_enable']) ? $_POST['mo_openid_auto_register_enable'] : 0);
				update_option( 'mo_openid_register_disabled_message', $_POST['mo_openid_register_disabled_message']);


				//email notification
				update_option( 'mo_openid_email_enable', isset( $_POST['mo_openid_email_enable']) ? $_POST['mo_openid_email_enable'] : 0);

				//Customized text
			    update_option('mo_openid_login_widget_customize_text',$_POST['mo_openid_login_widget_customize_text'] );
			    update_option( 'mo_openid_login_button_customize_text',$_POST['mo_openid_login_button_customize_text'] );

                //profile completion
                update_option('mo_openid_enable_profile_completion', isset( $_POST['mo_openid_enable_profile_completion']) ? $_POST['mo_openid_enable_profile_completion'] : 0);

                if(get_option('mo_openid_enable_profile_completion') == 1) {
                        // update_option('mo_profile_completion_form_title', $_POST['mo_profile_completion_form_title']);
                         //update_option('mo_profile_completion_form_message', $_POST['mo_profile_completion_form_message']);

                         update_option('mo_profile_complete_title', $_POST['mo_profile_complete_title']);
                         update_option('mo_profile_complete_username_label', $_POST['mo_profile_complete_username_label']);
                         update_option('mo_profile_complete_email_label', $_POST['mo_profile_complete_email_label']);
                         update_option('mo_profile_complete_submit_button', $_POST['mo_profile_complete_submit_button']);
                         update_option('mo_profile_complete_instruction', $_POST['mo_profile_complete_instruction']);
                         update_option('mo_profile_complete_extra_instruction', $_POST['mo_profile_complete_extra_instruction']);
                         update_option('mo_profile_complete_uname_exist', $_POST['mo_profile_complete_uname_exist']);

                         update_option('mo_email_verify_resend_otp_button', $_POST['mo_email_verify_resend_otp_button']);
                         update_option('mo_email_verify_back_button', $_POST['mo_email_verify_back_button']);
                         update_option('mo_email_verify_title', $_POST['mo_email_verify_title']);
                         update_option('mo_email_verify_message', $_POST['mo_email_verify_message']);
                         update_option('mo_email_verify_verification_code_instruction', $_POST['mo_email_verify_verification_code_instruction']);
                         update_option('mo_email_verify_wrong_otp', $_POST['mo_email_verify_wrong_otp']);
                     }
                //account-linking
                update_option( 'mo_openid_account_linking_enable', isset( $_POST['mo_openid_account_linking_enable']) ? $_POST['mo_openid_account_linking_enable'] : 0);

                 if(get_option('mo_openid_account_linking_enable') == 1 && (!mo_openid_restrict_user()))
                 {
                    // update_option('mo_account_linking_form_title', $_POST['mo_account_linking_form_title']);
                     //update_option('mo_account_linking_form_message', $_POST['mo_account_linking_form_message']);

                     update_option('mo_account_linking_title', $_POST['mo_account_linking_title']);
                     update_option('mo_account_linking_new_user_button', $_POST['mo_account_linking_new_user_button']);
                     update_option('mo_account_linking_existing_user_button', $_POST['mo_account_linking_existing_user_button']);
                     update_option('mo_account_linking_new_user_instruction', $_POST['mo_account_linking_new_user_instruction']);
                     update_option('mo_account_linking_existing_user_instruction', $_POST['mo_account_linking_existing_user_instruction']);
                     update_option('mo_account_linking_extra_instruction', $_POST['mo_account_linking_extra_instruction']);


                 }



                if(!get_option('mo_openid_oauth')){
                    update_option('mo_openid_login_widget_customize_logout_name_text',sanitize_text_field($_POST['mo_openid_login_widget_customize_logout_name_text']));
                    update_option( 'mo_openid_login_widget_customize_logout_text',sanitize_text_field($_POST['mo_openid_login_widget_customize_logout_text']));
					update_option('moopenid_logo_check', isset( $_POST['moopenid_logo_check']) ? $_POST['moopenid_logo_check'] : 0);

				}else{
                    update_option('moopenid_logo_check', isset( $_POST['moopenid_logo_check']) ? $_POST['moopenid_logo_check'] : 0);
                }

				update_option('mo_login_openid_login_widget_customize_textcolor',$_POST['mo_login_openid_login_widget_customize_textcolor']);
			    update_option('mo_openid_login_theme',$_POST['mo_openid_login_theme'] );
				update_option( 'mo_openid_message', 'Your settings are saved successfully.' );

				//customization of icons
				update_option('mo_login_icon_custom_size',$_POST['mo_login_icon_custom_size'] );
				update_option('mo_login_icon_space',$_POST['mo_login_icon_space'] );
				update_option('mo_login_icon_custom_width',$_POST['mo_login_icon_custom_width'] );
				update_option('mo_login_icon_custom_height',$_POST['mo_login_icon_custom_height'] );
				update_option('mo_openid_login_custom_theme',$_POST['mo_openid_login_custom_theme'] );
				update_option( 'mo_login_icon_custom_color', $_POST['mo_login_icon_custom_color'] );
				update_option('mo_login_icon_custom_boundary',$_POST['mo_login_icon_custom_boundary']);

				// avatar
				update_option( 'moopenid_social_login_avatar', isset( $_POST['moopenid_social_login_avatar']) ? $_POST['moopenid_social_login_avatar'] : 0);


				if(isset($_POST['mapping_value_default']))
					update_option('mo_openid_login_role_mapping', isset( $_POST['mapping_value_default']) ? $_POST['mapping_value_default'] : 'subscriber');

				if(mo_openid_is_customer_valid() && !mo_openid_get_customer_plan('Do It Yourself')){
					//Attribute collection
					update_option( 'moopenid_user_attributes', isset( $_POST['moopenid_user_attributes']) ? $_POST['moopenid_user_attributes'] : 0);
				}

				$this->mo_openid_show_success_message();
                if(!mo_openid_is_customer_registered()) {
                    $redirect = add_query_arg( array('tab' => 'register'), $_SERVER['REQUEST_URI'] );
                    update_option('mo_openid_message', 'Your settings are successfully saved. Please  <a href=\" '. $redirect .'\">Register or Login with miniOrange</a>  to enable Social Login and Social Sharing.');
                    $this->mo_openid_show_error_message();
                }


		}else if( isset( $_POST['option'] ) and $_POST['option'] == "mo_openid_save_woocommerce_field" ) {
            //woocommerce integration
            // woocommerce display options
            if(!mo_openid_restrict_user()) {

                update_option('mo_openid_woocommerce_login_form', isset($_POST['mo_openid_woocommerce_login_form']) ? $_POST['mo_openid_woocommerce_login_form'] : 0);
                update_option('mo_openid_woocommerce_before_login_form', isset($_POST['mo_openid_woocommerce_before_login_form']) ? $_POST['mo_openid_woocommerce_before_login_form'] : 0);
                update_option('mo_openid_woocommerce_center_login_form', isset($_POST['mo_openid_woocommerce_center_login_form']) ? $_POST['mo_openid_woocommerce_center_login_form'] : 0);
                update_option('mo_openid_woocommerce_register_form_start', isset($_POST['mo_openid_woocommerce_register_form_start']) ? $_POST['mo_openid_woocommerce_register_form_start'] : 0);
                update_option('mo_openid_woocommerce_center_register_form', isset($_POST['mo_openid_woocommerce_center_register_form']) ? $_POST['mo_openid_woocommerce_center_register_form'] : 0);
                update_option('mo_openid_woocommerce_register_form_end', isset($_POST['mo_openid_woocommerce_register_form_end']) ? $_POST['mo_openid_woocommerce_register_form_end'] : 0);
                update_option('mo_openid_woocommerce_before_checkout_billing_form', isset($_POST['mo_openid_woocommerce_before_checkout_billing_form']) ? $_POST['mo_openid_woocommerce_before_checkout_billing_form'] : 0);
                update_option('mo_openid_woocommerce_after_checkout_billing_form', isset($_POST['mo_openid_woocommerce_after_checkout_billing_form']) ? $_POST['mo_openid_woocommerce_after_checkout_billing_form'] : 0);
            }

        }else if( isset( $_POST['option'] ) and $_POST['option'] == "mo_openid_save_buddypress_field" ){

            //buddypress display options
            if(!mo_openid_restrict_user()) {
                update_option('mo_openid_bp_before_register_page', isset($_POST['mo_openid_bp_before_register_page']) ? $_POST['mo_openid_bp_before_register_page'] : 0);
                update_option('mo_openid_bp_before_account_details_fields', isset($_POST['mo_openid_bp_before_account_details_fields']) ? $_POST['mo_openid_bp_before_account_details_fields'] : 0);
                update_option('mo_openid_bp_after_register_page', isset($_POST['mo_openid_bp_after_register_page']) ? $_POST['mo_openid_bp_after_register_page'] : 0);
            }


        }
		else if( isset( $_POST['option'] ) and $_POST['option'] == "mo_openid_save_comment_settings" ) {


				//commenting
				update_option( 'mo_openid_social_comment_fb', isset( $_POST['mo_openid_social_comment_fb']) ? $_POST['mo_openid_social_comment_fb'] : 0);
				update_option( 'mo_openid_social_comment_google', isset( $_POST['mo_openid_social_comment_google']) ? $_POST['mo_openid_social_comment_google'] : 0);
				update_option( 'mo_openid_social_comment_default', isset( $_POST['mo_openid_social_comment_default']) ? $_POST['mo_openid_social_comment_default'] : 0);

				//comment position
				update_option( 'mo_openid_social_comment_blogpost', isset( $_POST['mo_openid_social_comment_blogpost']) ? $_POST['mo_openid_social_comment_blogpost'] : 0);
				update_option( 'mo_openid_social_comment_static', isset( $_POST['mo_openid_social_comment_static']) ? $_POST['mo_openid_social_comment_static'] : 0);

				//comment labels
				update_option('mo_openid_social_comment_default_label',$_POST['mo_openid_social_comment_default_label'] );
			    update_option('mo_openid_social_comment_fb_label',$_POST['mo_openid_social_comment_fb_label'] );
			    update_option('mo_openid_social_comment_google_label',$_POST['mo_openid_social_comment_google_label'] );
			    update_option('mo_openid_social_comment_heading_label',$_POST['mo_openid_social_comment_heading_label'] );

			    update_option( 'mo_openid_message', 'Your settings are saved successfully.' );
			    $this->mo_openid_show_success_message();
            if(!mo_openid_is_customer_registered()) {
                $redirect = add_query_arg( array('tab' => 'register'), $_SERVER['REQUEST_URI'] );
                update_option('mo_openid_message', 'Your settings are successfully saved. Please  <a href=\" '. $redirect .'\">Register or Login with miniOrange</a>  to enable Social Login and Social Sharing.');
                $this->mo_openid_show_error_message();
            }


		}
		else if( isset( $_POST['option'] ) and $_POST['option'] == "mo_openid_contact_us_query_option" ) {
			// Contact Us query
			$email = $_POST['mo_openid_contact_us_email'];
			$phone = $_POST['mo_openid_contact_us_phone'];
			$query = $_POST['mo_openid_contact_us_query'];
			$customer = new CustomerOpenID();
			if ( $this->mo_openid_check_empty_or_null( $email ) || $this->mo_openid_check_empty_or_null( $query ) ) {
				update_option('mo_openid_message', 'Please fill up Email and Query fields to submit your query.');
				$this->mo_openid_show_error_message();
			} else {
				$submited = $customer->submit_contact_us( $email, $phone, $query );
				if ( $submited == false ) {
					update_option('mo_openid_message', 'Your query could not be submitted. Please try again.');
					$this->mo_openid_show_error_message();
				} else {
					update_option('mo_openid_message', 'Thanks for getting in touch! We shall get back to you shortly.');
					$this->mo_openid_show_success_message();
				}
			}
		}
		else if( isset( $_POST['option'] ) and $_POST['option'] == "mo_openid_resend_otp" ) {

		    $customer = new CustomerOpenID();
			$content = json_decode($customer->send_otp_token('EMAIL'), true);
			if(strcasecmp($content['status'], 'SUCCESS') == 0) {
				if(get_option('mo_openid_email_otp_count')){
					update_option('mo_openid_email_otp_count',get_option('mo_openid_email_otp_count') + 1);
					update_option('mo_openid_message', 'Another One Time Passcode has been sent <b>( ' . get_option('mo_openid_email_otp_count') . ' )</b> for verification to ' . get_option('mo_openid_admin_email'));
				}else{
					update_option( 'mo_openid_message', ' A passcode is sent to ' . get_option('mo_openid_admin_email') . '. Please enter the otp here to verify your email.');
					update_option('mo_openid_email_otp_count',1);
				}
				update_option('mo_openid_transactionId',$content['txId']);
				update_option('mo_openid_registration_status','MO_OTP_DELIVERED_SUCCESS');
				$this->mo_openid_show_success_message();
			}else{
				update_option('mo_openid_message','There was an error in sending email. Please click on Resend OTP to try again.');
				update_option('mo_openid_registration_status','MO_OTP_DELIVERED_FAILURE');
				$this->mo_openid_show_error_message();
			}

		}else if( isset( $_POST['option'] ) and $_POST['option'] == "mo_openid_go_back" ){
			update_option('mo_openid_registration_status','');
			delete_option('mo_openid_new_registration');
			delete_option('mo_openid_admin_email');
			delete_option('mo_openid_sms_otp_count');
			delete_option('mo_openid_email_otp_count');

		}else if( isset( $_POST['option'] ) and $_POST['option'] == "mo_openid_go_back_login" ){
			update_option('mo_openid_registration_status','');
			delete_option('mo_openid_admin_email');
			delete_option('mo_openid_admin_phone');
			delete_option('mo_openid_admin_password');
			delete_option('mo_openid_admin_customer_key');
			delete_option('mo_openid_verify_customer');

		}else if( isset( $_POST['option'] ) and $_POST['option'] == "mo_openid_go_back_registration" ){
			update_option('mo_openid_verify_customer','true');

		}else if( isset( $_POST['option'] ) and $_POST['option'] == "mo_openid_save_other_settings" ){

				update_option( 'mo_openid_google_share_enable', isset( $_POST['mo_openid_google_share_enable']) ? $_POST['mo_openid_google_share_enable'] : 0);
				update_option( 'mo_openid_facebook_share_enable', isset( $_POST['mo_openid_facebook_share_enable']) ? $_POST['mo_openid_facebook_share_enable'] : 0);
				update_option( 'mo_openid_linkedin_share_enable', isset( $_POST['mo_openid_linkedin_share_enable']) ? $_POST['mo_openid_linkedin_share_enable'] : 0);
				update_option( 'mo_openid_reddit_share_enable', isset( $_POST['mo_openid_reddit_share_enable']) ? $_POST['mo_openid_reddit_share_enable'] : 0);
				update_option( 'mo_openid_pinterest_share_enable', isset( $_POST['mo_openid_pinterest_share_enable']) ? $_POST['mo_openid_pinterest_share_enable'] : 0);
				update_option( 'mo_openid_twitter_share_enable', isset( $_POST['mo_openid_twitter_share_enable']) ? $_POST['mo_openid_twitter_share_enable'] : 0);
				update_option( 'mo_openid_tumblr_share_enable', isset( $_POST['mo_openid_tumblr_share_enable']) ? $_POST['mo_openid_tumblr_share_enable'] : 0);
				update_option( 'mo_openid_delicious_share_enable', isset( $_POST['mo_openid_delicious_share_enable']) ? $_POST['mo_openid_delicious_share_enable'] : 0);
				update_option( 'mo_openid_vkontakte_share_enable', isset( $_POST['mo_openid_vkontakte_share_enable']) ? $_POST['mo_openid_vkontakte_share_enable'] : 0);
				update_option( 'mo_openid_stumble_share_enable', isset( $_POST['mo_openid_stumble_share_enable']) ? $_POST['mo_openid_stumble_share_enable'] : 0);
				update_option( 'mo_openid_odnoklassniki_share_enable', isset( $_POST['mo_openid_odnoklassniki_share_enable']) ? $_POST['mo_openid_odnoklassniki_share_enable'] : 0);
				update_option( 'mo_openid_digg_share_enable', isset( $_POST['mo_openid_digg_share_enable']) ? $_POST['mo_openid_digg_share_enable'] : 0);
				update_option( 'mo_openid_pocket_share_enable', isset( $_POST['mo_openid_pocket_share_enable']) ? $_POST['mo_openid_pocket_share_enable'] : 0);

				update_option( 'mo_openid_mail_share_enable', isset( $_POST['mo_openid_mail_share_enable']) ? $_POST['mo_openid_mail_share_enable'] : 0);
				update_option( 'mo_openid_print_share_enable', isset( $_POST['mo_openid_print_share_enable']) ? $_POST['mo_openid_print_share_enable'] : 0);
				update_option( 'mo_openid_whatsapp_share_enable', isset( $_POST['mo_openid_whatsapp_share_enable']) ? $_POST['mo_openid_whatsapp_share_enable'] : 0);

				update_option('mo_share_options_enable_home_page',isset( $_POST['mo_share_options_home_page']) ? $_POST['mo_share_options_home_page'] : 0);
				update_option('mo_share_options_enable_post',isset( $_POST['mo_share_options_post']) ? $_POST['mo_share_options_post'] : 0);
				update_option('mo_share_options_enable_static_pages',isset( $_POST['mo_share_options_static_pages']) ? $_POST['mo_share_options_static_pages'] : 0);
				update_option('mo_share_options_wc_sp_summary',isset( $_POST['mo_share_options_wc_sp_summary']) ? $_POST['mo_share_options_wc_sp_summary'] : 0);
				update_option('mo_share_options_wc_sp_summary_top',isset( $_POST['mo_share_options_wc_sp_summary_top']) ? $_POST['mo_share_options_wc_sp_summary_top'] : 0);
				update_option('mo_share_options_enable_post_position',$_POST['mo_share_options_enable_post_position'] );
				update_option('mo_share_options_home_page_position',$_POST['mo_share_options_home_page_position'] );
				update_option('mo_share_options_static_pages_position',$_POST['mo_share_options_static_pages_position'] );
				update_option('mo_share_options_bb_forum_position',$_POST['mo_share_options_bb_forum_position'] );
				update_option('mo_share_options_bb_topic_position',$_POST['mo_share_options_bb_topic_position'] );
				update_option('mo_share_options_bb_reply_position',$_POST['mo_share_options_bb_reply_position'] );
				update_option('mo_openid_share_theme',$_POST['mo_openid_share_theme'] );
				update_option('mo_share_vertical_hide_mobile',isset( $_POST['mo_share_vertical_hide_mobile']) ? $_POST['mo_share_vertical_hide_mobile'] : 0);
				update_option('mo_share_options_bb_forum',isset( $_POST['mo_share_options_bb_forum']) ? $_POST['mo_share_options_bb_forum'] : 0);
				update_option('mo_share_options_bb_topic',isset( $_POST['mo_share_options_bb_topic']) ? $_POST['mo_share_options_bb_topic'] : 0);
				update_option('mo_share_options_bb_reply',isset( $_POST['mo_share_options_bb_reply']) ? $_POST['mo_share_options_bb_reply'] : 0);
				update_option('mo_openid_share_widget_customize_text',$_POST['mo_openid_share_widget_customize_text'] );
				update_option('mo_openid_share_widget_customize_text_color',$_POST['mo_openid_share_widget_customize_text_color'] );
				update_option('mo_openid_share_twitter_username', sanitize_text_field($_POST['mo_openid_share_twitter_username'])) ;
				update_option('mo_openid_share_email_subject', sanitize_text_field($_POST['mo_openid_share_email_subject'])) ;
				update_option('mo_openid_share_email_body', sanitize_text_field($_POST['mo_openid_share_email_body'])) ;

				update_option('mo_openid_share_widget_customize_direction_horizontal',isset( $_POST['mo_openid_share_widget_customize_direction_horizontal']) ? $_POST['mo_openid_share_widget_customize_direction_horizontal'] : 0);
				update_option('mo_openid_share_widget_customize_direction_vertical',isset( $_POST['mo_openid_share_widget_customize_direction_vertical']) ? $_POST['mo_openid_share_widget_customize_direction_vertical'] : 0);
				update_option('mo_sharing_icon_custom_size',isset( $_POST['mo_sharing_icon_custom_size']) ? $_POST['mo_sharing_icon_custom_size'] : 35);
				update_option('mo_sharing_icon_custom_color',$_POST['mo_sharing_icon_custom_color'] );
				update_option('mo_openid_share_custom_theme',$_POST['mo_openid_share_custom_theme'] );
				update_option('mo_sharing_icon_custom_font',$_POST['mo_sharing_icon_custom_font'] );
				update_option('mo_sharing_icon_space',$_POST['mo_sharing_icon_space'] );
				update_option( 'mo_openid_message', 'Your settings are saved successfully.' );
				$this->mo_openid_show_success_message();
            if(!mo_openid_is_customer_registered()) {
                $redirect = add_query_arg( array('tab' => 'register'), $_SERVER['REQUEST_URI'] );
                update_option('mo_openid_message', 'Your settings are successfully saved. Please  <a href=\" '. $redirect .'\">Register or Login with miniOrange</a>  to enable Social Login and Social Sharing.');
                $this->mo_openid_show_error_message();
            }
		}
		else if( isset( $_POST['option'] ) and $_POST['option'] == "mo_openid_add_custom_app" ) {
			if($this->mo_oauth_check_empty_or_null($_POST['mo_oauth_client_id']) || $this->mo_oauth_check_empty_or_null($_POST['mo_oauth_client_secret'])) {
				update_option( 'message', 'Please enter valid Client ID and Client Secret.');
				$this->mo_openid_show_error_message();
				return;
			} else{
				$scope = stripslashes(sanitize_text_field( $_POST['mo_oauth_scope'] ));
				$clientid = stripslashes(sanitize_text_field( $_POST['mo_oauth_client_id'] ));
				$clientsecret = stripslashes(sanitize_text_field( $_POST['mo_oauth_client_secret'] ));
				$appname = stripslashes(sanitize_text_field( $_POST['mo_oauth_app_name'] ));

				if(get_option('mo_openid_apps_list'))
					$appslist = get_option('mo_openid_apps_list');
				else
					$appslist = array();

				$newapp = array();

				foreach($appslist as $key => $currentapp){
					if($appname == $key){
						$newapp = $currentapp;
						break;
					}
				}

				$newapp['clientid'] = $clientid;
				$newapp['clientsecret'] = $clientsecret;
				$newapp['scope'] = $scope;
				$newapp['redirecturi'] = site_url().'/openidcallback';
				if($appname=="facebook"){
					$authorizeurl = 'https://www.facebook.com/dialog/oauth';
					$accesstokenurl = 'https://graph.facebook.com/v2.8/oauth/access_token';
					$resourceownerdetailsurl = 'https://graph.facebook.com/me/?fields=id,name,email,age_range,first_name,gender,last_name,link&access_token=';
				} else if($appname=="google"){
					$authorizeurl = "https://accounts.google.com/o/oauth2/auth";
					$accesstokenurl = "https://www.googleapis.com/oauth2/v3/token";
					//private static final String VERIFY_TOKEN_ENDPOINT = "https://www.googleapis.com/oauth2/v1/tokeninfo?id_token=";
					//private static final String GET_USER_INFO_ENDPOINT = "https://www.googleapis.com/oauth2/v1/userinfo?alt=json&access_token=";
					$resourceownerdetailsurl = "https://www.googleapis.com/plus/v1/people/me";
				} else if($appname=="twitter"){
					$authorizeurl = "https://api.twitter.com/oauth/authorize";
					$accesstokenurl = "https://api.twitter.com/oauth/access_token";
					$resourceownerdetailsurl = "https://dev.twitter.com/docs/api/1.1/get/account/verify_credentials?include_email=true";
				}else {
					$authorizeurl = stripslashes(sanitize_text_field($_POST['mo_oauth_authorizeurl']));
					$accesstokenurl = stripslashes(sanitize_text_field($_POST['mo_oauth_accesstokenurl']));
					$resourceownerdetailsurl = stripslashes(sanitize_text_field($_POST['mo_oauth_resourceownerdetailsurl']));
					$appname = stripslashes(sanitize_text_field( $_POST['mo_oauth_custom_app_name'] ));
				}

				$newapp['authorizeurl'] = $authorizeurl;
				$newapp['accesstokenurl'] = $accesstokenurl;
				$newapp['resourceownerdetailsurl'] = $resourceownerdetailsurl;
				$appslist[$appname] = $newapp;
				update_option('mo_openid_apps_list', $appslist);
				wp_redirect('admin.php?page=mo_openid_settings&tab=custom_app');
			}
		}
		else if( isset( $_POST['option'] ) and $_POST['option'] == "mo_openid_custom_app_attribute_mapping" ) {
			$appname = stripslashes(sanitize_text_field( $_POST['mo_oauth_app_name'] ));
			$email_attr = stripslashes(sanitize_text_field( $_POST['mo_oauth_email_attr'] ));
			$name_attr = stripslashes(sanitize_text_field( $_POST['mo_oauth_name_attr'] ));

			$appslist = get_option('mo_openid_apps_list');
			foreach($appslist as $key => $currentapp){
				if($appname == $key){
					$currentapp['email_attr'] = $email_attr;
					$currentapp['name_attr'] = $name_attr;
					$appslist[$key] = $currentapp;
					break;
				}
			}
			update_option('mo_openid_apps_list', $appslist);
			update_option( 'message', 'Your settings are saved successfully.' );
			$this->mo_openid_show_success_message();
			wp_redirect('admin.php?page=mo_openid_settings&tab=custom_app&action=update&app='.urlencode($appname));
		}
		}


        if(isset($_POST['mo_openid_option']) and $_POST['mo_openid_option']=='mo_openid_skip_feedback'){

            update_option('mo_openid_feedback_form',1);
            deactivate_plugins( '/miniorange-login-openid/miniorange_openid_sso_settings.php' );

        }
        if(isset($_POST['mo_openid_feedback']) and $_POST['mo_openid_feedback']=='mo_openid_feedback')
        {
            $message='';
            if(isset($_POST['deactivate_plugin']) )
            {
                $message.=' '. $_POST['deactivate_plugin'];
                if($_POST['mo_openid_query_feedback']!='')
                {
                    $message.='. '.$_POST['mo_openid_query_feedback'];
                }

                if(get_option('mo_openid_admin_email'))
                {
                    $email=get_option('mo_openid_admin_email');
                }else {
                    $user_info = get_userdata(get_current_user_id());
                    $email = $user_info->user_email;
                }

                //only reason
                $phone='';
                $contact_us = new CustomerOpenID();
                $submited = json_decode( $contact_us->mo_openid_send_email_alert( $email, $phone, $message ), true );

                if ( json_last_error() == JSON_ERROR_NONE ) {
                    if ( is_array( $submited ) && array_key_exists( 'status', $submited ) && $submited['status'] == 'ERROR' )
                    {
                       if( isset($submited['message']))
                           {
                               update_option('mo_openid_message',$submited['message']);
                               $this->mo_openid_show_error_message();
                           }


                    } else
                    {
                        if ( $submited == false )
                        {
                            update_option( 'mo_openid_message',"ERROR_WHILE_SUBMITTING_QUERY");
                            $this->mo_openid_show_success_message();
                        } else {

                            update_option('mo_openid_message',"Your response is submitted successfully");
                            $this->mo_openid_show_success_message();
                            update_option('mo_openid_feedback_form',1);
                        }
                    }
                }
                update_option('mo_openid_feedback_form',1);
                deactivate_plugins( '/miniorange-login-openid/miniorange_openid_sso_settings.php' );



            }
        }

	}

	function create_customer(){
		delete_option('mo_openid_sms_otp_count');
		delete_option('mo_openid_email_otp_count');
		$customer = new CustomerOpenID();
		$customerKey = json_decode( $customer->create_customer(), true );
		if( strcasecmp( $customerKey['status'], 'CUSTOMER_USERNAME_ALREADY_EXISTS') == 0 ) {
			$this->get_current_customer();
		} else if( strcasecmp( $customerKey['status'], 'SUCCESS' ) == 0 ) {
			update_option( 'mo_openid_admin_customer_key', $customerKey['id'] );
			update_option( 'mo_openid_admin_api_key', $customerKey['apiKey'] );
			update_option( 'mo_openid_customer_token', $customerKey['token'] );
			update_option('mo_openid_admin_password', '');
			update_option('mo_openid_cust', '0');
			update_option( 'mo_openid_message', 'Registration complete!');
			update_option('mo_openid_registration_status','MO_OPENID_REGISTRATION_COMPLETE');
			delete_option('mo_openid_verify_customer');
			delete_option('mo_openid_new_registration');
			$this->mo_openid_show_success_message();
			header('Location: admin.php?page=mo_openid_settings&tab=pricing');
		}
		update_option('mo_openid_admin_password', '');
	}

	function get_current_customer(){
		$customer = new CustomerOpenID();
		$content = $customer->get_customer_key();
		$customerKey = json_decode( $content, true );

		if( isset($customerKey) ) {
			update_option( 'mo_openid_admin_customer_key', $customerKey['id'] );
			update_option( 'mo_openid_admin_api_key', $customerKey['apiKey'] );
			update_option( 'mo_openid_customer_token', $customerKey['token'] );
			update_option('mo_openid_admin_password', '' );
			update_option( 'mo_openid_message', 'Your account has been retrieved successfully.' );
			delete_option('mo_openid_verify_customer');
			delete_option('mo_openid_new_registration');
			$this->mo_openid_show_success_message();
		} else {
			update_option( 'mo_openid_message', 'You already have an account with miniOrange. Please enter a valid password.');
			update_option('mo_openid_verify_customer', 'true');
			delete_option('mo_openid_new_registration');
			$this->mo_openid_show_error_message();
		}

	}

	function miniorange_openid_menu() {

		//Add miniOrange plugin to the menu
		$page = add_menu_page( 'MO OpenID Settings ' . __( 'Configure OpenID', 'mo_openid_settings' ), 'miniOrange Social Login, Sharing', 'manage_options',
		'mo_openid_settings', array( $this, 'mo_login_widget_openid_options' ),plugin_dir_url(__FILE__) . 'includes/images/miniorange_icon.png');
	}

	function mo_openid_plugin_actions( $links, $file ) {
	 	if( $file == 'miniorange-login-openid/miniorange_openid_sso_settings.php' && function_exists( "admin_url" ) ) {
			$settings_link = '<a href="' . admin_url( 'tools.php?page=mo_openid_settings' ) . '">' . __('Settings') . '</a>';
			array_unshift( $links, $settings_link ); // before other links
		}
		return $links;
	}

	public function mo_get_output( $atts ){
		if(mo_openid_is_customer_registered()){
			$miniorange_widget = new mo_openid_login_wid();
			$html = $miniorange_widget->openidloginFormShortCode( $atts );
			return $html;
		}
	}

	public function mo_get_sharing_output( $atts ){
		if(mo_openid_is_customer_registered()){
			$title = '';
			global $post;
			if(isset($post)) {
				$content=get_the_content();
				$title = str_replace('+', '%20', urlencode($post->post_title));
				$content=strip_shortcodes( strip_tags( get_the_content() ) );
			}
			$html = mo_openid_share_shortcode( $atts, $title);
			return $html;
		}
	}

	public function mo_get_vertical_sharing_output( $atts ){
		if(mo_openid_is_customer_registered()){
			$title = '';
			global $post;
			if(isset($post)) {
				$content=get_the_content();
				$title = str_replace('+', '%20', urlencode($post->post_title));
				$content=strip_shortcodes( strip_tags( get_the_content() ) );
			}
			$html = mo_openid_vertical_share_shortcode( $atts, $title);
			return $html;
		}
	}

	public function mo_get_comments_output( $atts ){
		if(mo_openid_is_customer_registered()){
			$html = mo_openid_comments_shortcode();
			return $html;
		}
	}

	function mo_social_login_custom_avatar( $avatar, $mixed, $size, $default, $alt = '' ) {

        if ( is_numeric( $mixed ) AND $mixed > 0 ) {	//Check if we have a user identifier
            $user_id = $mixed;
        } elseif ( is_string( $mixed ) AND ( $user = get_user_by( 'email', $mixed )) ) {	//Check if we have a user email
        	$user_id = $user->ID;
        } elseif ( is_object( $mixed ) AND property_exists( $mixed, 'user_id' ) AND is_numeric( $mixed->user_id ) ) {		//Check if we have a user object
            $user_id = $mixed->user_id;
        } else {		//None found
            $user_id = null;
        }

        if (  !empty( $user_id ) ) {    //User found?
            $filename = '';
            if ($this->mo_openid_is_buddypress_active()) {
                $filename = bp_upload_dir();
                $filename = $filename['basedir'] . "/avatars/" . $user_id;
            }
            if (!(is_dir($filename))) {
                $user_meta_thumbnail = get_user_meta($user_id, 'moopenid_user_avatar', true);        //Read the avatar
                $user_meta_name = get_user_meta($user_id, 'user_name', true);        //read user details
                // if ( $options['apsl_user_avatar_options'] == 'social' ) {
                $user_picture = (!empty($user_meta_thumbnail) ? $user_meta_thumbnail : '');
                if ($user_picture !== false AND strlen(trim($user_picture)) > 0) {    //Avatar found?
                    return '<img alt="' . $user_meta_name . '" src="' . $user_picture . '" class="avatar apsl-avatar-social-login avatar-' . $size . ' photo" height="' . $size . '" width="' . $size . '" />';
                }
            }
        }
        return $avatar;
	}

	function mo_social_login_buddypress_avatar( $text, $args) {
		if(is_array($args)){
			if(!empty($args['object']) && strtolower($args['object']) == 'user'){
				if(!empty($args['item_id']) && is_numeric($args['item_id'])) {
                    $filename = '';
                    if ($this->mo_openid_is_buddypress_active()) {
                        $filename = bp_upload_dir();
                        $filename = $filename['basedir'] . "/avatars/" . $args['item_id'];
                    }
                    if (!(is_dir($filename))) {
                        if (($userdata = get_userdata($args['item_id'])) !== false) {
                            $user_meta_thumbnail = get_user_meta($userdata->ID, 'moopenid_user_avatar', true);        //Read the avatar
                            $user_meta_name = $userdata->user_login;        //read user details
                            $user_picture = (!empty($user_meta_thumbnail) ? $user_meta_thumbnail : '');
                            $size = (!empty($args['width']) ? 'width="' . $args['width'] . '" ' : 'width="50"');
                            if ($user_picture !== false AND strlen(trim($user_picture)) > 0) {    //Avatar found?
                                return '<img alt="' . $user_meta_name . '" src="' . $user_picture . '" class="avatar apsl-avatar-social-login avatar-' . $size . ' photo" height="' . $size . '" width="' . $size . '" />';
                            }
                        }
                    }
                }
			}
		}
		return $text;
	}

	function mo_social_login_custom_avatar_url( $url, $id_or_email, $args = null ) {
		if ( is_numeric( $id_or_email ) AND $id_or_email > 0 ) {	//Check if we have an user identifier
			$user_id = $id_or_email;
		} elseif ( is_string( $id_or_email ) AND ( $user = get_user_by( 'email', $id_or_email )) ) {	//Check if we have an user email
			$user_id = $user->ID;
		} elseif ( is_object( $id_or_email ) AND property_exists( $id_or_email, 'user_id' ) AND is_numeric( $id_or_email->user_id ) ) {		//Check if we have an user object
			$user_id = $id_or_email->user_id;
		} else {		//None found
			$user_id = null;
		}

		if (  !empty( $user_id ) ) {
            $filename = '';
            if ($this->mo_openid_is_buddypress_active()) {
                $filename = bp_upload_dir();
                $filename = $filename['basedir'] . "/avatars/" . $user_id;
            }
            if (!(is_dir($filename))) {
                $user_meta_thumbnail = get_user_meta($user_id, 'moopenid_user_avatar', true);
                $user_picture = (!empty($user_meta_thumbnail) ? $user_meta_thumbnail : $url);
                return $user_picture;
            }
        }
		return $url;
	}

    function mo_openid_is_buddypress_active(){
        include_once(ABSPATH.'wp-admin/includes/plugin.php');
        if(is_plugin_active('buddypress/bp-loader.php') )
            return true;
        else
            return false;
    }
}

new Miniorange_OpenID_SSO;
?>