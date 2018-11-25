<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class NF_FU_Fields_Upload
 */
class NF_FU_Fields_Upload extends NF_Abstracts_Field {

	protected $_nicename = 'File Upload';
	protected $_parent_type = 'textbox';
	protected $_section = 'common';
	protected $_templates = 'file-upload';
	protected $_icon = 'file';
    protected $_test_value = false;
	protected $_settings_all_fields = array(
		'key',
		'label',
		'label_pos',
		'required',
		'classes',
		'manual_key',
		'help',
		'description',
	);

	public function __construct() {
		$this->_name = NF_FU_File_Uploads::TYPE;
		$this->_type = NF_FU_File_Uploads::TYPE;

		parent::__construct();

		$settings = NF_File_Uploads()->config( 'field-settings' );

		$this->_settings = array_merge( $this->_settings, $settings );

        add_action( 'nf_admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	/**
	 * Save the temp file
	 *
	 * @param $field
	 * @param $data
	 *
	 * @return mixed
	 */
	public function process( $field, $data ) {

		/*
		 * If we don't have any files set or we are saving progress, bail early.
		 */
		if ( ! isset( $field['files'] ) || empty( $field['files'] ) || ( isset ( $data[ 'extra' ][ 'saveProgress' ] ) && 1 == $data[ 'extra' ][ 'saveProgress' ] ) ) {
			return $data;
		}

		$submission_data = array();

		// Get common data
		$user_id  = $this->get_user_id();
		$base_dir = NF_File_Uploads()->controllers->uploads->get_path( '' );
		$base_url = NF_File_Uploads()->controllers->uploads->get_url( '' );
		$base_dir .= $data['form_id'] . '/';
		$base_url .= $data['form_id'] . '/';
		wp_mkdir_p( $base_dir );

		// Get custom directory using common data for shortcodes
		$custom_upload_dir    = NF_File_Uploads()->controllers->settings->custom_upload_dir();
		$is_custom_upload_dir = false;
		if ( ! empty( $custom_upload_dir ) ) {
			NF_File_Uploads()->controllers->custom_paths->set_data( 'formtitle', $data['settings']['title'] );
			$custom_upload_dir = stripslashes( trim( $custom_upload_dir ) );
			$custom_upload_dir = NF_File_Uploads()->controllers->custom_paths->replace_shortcodes( $custom_upload_dir );

			if ( false === strpos( $custom_upload_dir, '%filename%' ) ) {
				// No more shortcode replacements to do
				wp_mkdir_p( $base_dir . $custom_upload_dir );
			} else {
				$is_custom_upload_dir = true;
			}
		}

		// Loop through all files
		foreach ( $field['files'] as $file_key => $file ) {
			$tmp_file = NF_File_Uploads()->controllers->uploads->get_path( $file['tmp_name'], true );

			// Remove the extension from the file name
			$file_parts = explode( '.', $file['name'] );
			$ext        = array_pop( $file_parts );

			// Set the filename custom shortcode
			NF_File_Uploads()->controllers->custom_paths->set_data( 'filename', implode( '.', $file_parts ) );
			$file_name = $file['name'];

			// Replace %filename% in custom dir
			if ( $is_custom_upload_dir ) {
				$custom_upload_dir = NF_File_Uploads()->controllers->custom_paths->replace_shortcode( $custom_upload_dir, 'filename' );
			}

			// Custom renaming of files
			if ( ! empty( $field['upload_rename'] ) ) {
				$file_name = NF_File_Uploads()->controllers->custom_paths->replace_shortcodes( $field['upload_rename'] );
				$file_name = NF_File_Uploads()->controllers->custom_paths->replace_field_shortcodes( $file_name, $data['fields'] );

				$file_name .= '.' . $ext;
			}

			$target_file = trailingslashit( $base_dir . ltrim( $custom_upload_dir, '/' ) ) . $file_name;
			// Ensure the path exists
			wp_mkdir_p( dirname( $target_file ) );

			if ( file_exists( $target_file ) ) {
				// Make sure we use a filename that is unique
				$original_target_file = $target_file;

				$i = 0;
				do {
					$i++;
					$target_file = str_replace( '.' . $ext, '-' . $i . '.' . $ext, $original_target_file );
				} while ( file_exists( $target_file ) );

				$file_name = basename( $target_file );
			}

			$file_url = trailingslashit( $base_url . ltrim( $custom_upload_dir, '/' ) ) . $file_name;

			// Move to permanent location
			$result = rename( $tmp_file, $target_file );
			if ( false === $result ) {
				$data['errors']['fields'][ $field['id'] ] = __( 'File upload error' );

				return $data;
			}

			// Add to FU table
			$file_data = array(
				'user_file_name'  => $file['name'],
				'file_name'       => $file_name,
				'file_path'       => $target_file,
				'file_url'        => $file_url,
				'upload_location' => 'server',
				'complete'        => 0,
			);

			$upload_id = NF_File_Uploads()->model->insert( $user_id, $data['form_id'], $field['id'], $file_data );

			$file_data['upload_id'] = $upload_id;

			// Save to media library
			if ( "1" == $field['media_library'] ) {
				$file_data['attachment_id'] = NF_File_Uploads()->controllers->uploads->create_attachment( $target_file, $file_name );
			}

			NF_File_Uploads()->model->update( $upload_id, $file_data );

			// Store FU data in the processing object
			$field['files'][ $file_key ]['data'] = $file_data;

			$submission_data[ $upload_id ] = $file_url;
		}

		foreach ( $data['fields'] as $key => $data_field ) {
			if ( $data_field['id'] != $field['id'] ) {
				continue;
			}

			// Set the field value to the array of file upload data
			$data['fields'][ $key ]['value'] = $submission_data;
			// Persist the data for each file
			$data['fields'][ $key ]['files'] = $field['files'];

			break;
		}

		return $data;
	}

	/**
	 * Get current logged in user ID
	 *
	 * @return int
	 */
	protected function get_user_id() {
		$current_user = wp_get_current_user();

		return ( $current_user ) ? $current_user->ID : 0;
	}

	/**
	 * Display uploads URL
	 *
	 * @param int   $id
	 * @param mixed $value
	 *
	 * @return string
	 */
	public function admin_form_element( $id, $value ) {
		if ( ! is_array( $value ) || empty( $value ) ) {
			return $value;
		}

		$new_value = array();
		foreach ( $value as $upload_id => $file_url ) {
			if ( is_array( $file_url ) ) {
				// FU 2.9.x data format
				$upload_id = $file_url['upload_id'];
				$file_url  = $file_url['file_url'];
			}

			$upload = NF_File_Uploads()->controllers->uploads->get( $upload_id );

			if ( false !== $upload ) {
				$file_url = NF_File_Uploads()->controllers->uploads->get_file_url( $file_url, $upload->data );
			}

			$new_value[] = sprintf( '<a href="%1$s" target="_blank">%2$s</a><br><input class="widefat" disabled type="text" value="%1$s">', $file_url, __( 'View', 'ninja-forms-uploads' ) );
		}

		return implode( '<br>', $new_value );
	}

	/**
	 * Send already saved files to the front end
	 *
	 * @param array $settings
	 * @param int   $form_id
	 *
	 * @return array
	 */
	public function localize_settings( $settings, $form_id ) {
		// TODO get saved uploads for existing submission
		// $files = array();
		// $settings['files'] = $files;


		// If a max file size is defined for the field use that, else you the global setting
		if ( ! isset( $settings['max_file_size'] ) || empty( $settings['max_file_size'] ) ) {
			$max_file_size_mb = NF_File_Uploads()->controllers->settings->get_max_file_size_mb();
		} else {
			$max_file_size_mb = $settings['max_file_size'];
		}

		$settings['max_file_size_mb'] = $max_file_size_mb;
		$max_file_size    = NF_File_Uploads()->controllers->settings->max_filesize( $max_file_size_mb );
		$settings['max_file_size'] = $max_file_size;
		$settings['uploadNonce'] = wp_create_nonce( 'nf-file-upload-' . $settings['id'] );

		return $settings;
	}

	/**
	 * Load admin scripts for the builder
	 */
	public function admin_enqueue_scripts() {
	    $ver = NF_File_Uploads()->plugin_version;
		$url = plugin_dir_url( NF_File_Uploads()->plugin_file_path );
		wp_enqueue_script( 'nf-fu-file-upload', $url . 'assets/js/builder/controllers/fieldFile.js', array( 'nf-builder' ), $ver );
	}
}
