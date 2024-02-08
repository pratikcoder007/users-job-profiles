<?php
/**
 * Plugin Name:       Users Job Profiles
 * Description:       This plugin is used to create a profile listing page with search and sorting functionality. To display on the frontend, please use this shortcode: [wcj_profiles]
 * Version:           1.0.3
 * Author:            Pratik
 * Text Domain:       users-job-profiles
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Users_Job_Profiles' ) ) :

	/**
	 * Main Users_Job_Profiles Class
	 *
	 * @class   Users_Job_Profiles
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	class Users_Job_Profiles {

		/**
		 * Users Job Profiles version.
		 *
		 * @var   string
		 * @since 2.4.7
		 */
		public $version = '1.0.4';

		/**
		 * Users_Job_Profiles Constructor.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function __construct() {

			// Include other necessary files.
			require_once 'admin/class-wcj-profiles.php';
			require_once 'admin/class-wcj-profile-metaboxes.php';
			require_once 'public/class-wcj-profiles-shortcode.php';

			// Enqueue assets.
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		}

		/**
		 * Enqueue CSS and JS assets.
		 */
		public function enqueue_assets() {
			// Enqueue CSS.
			wp_enqueue_style( 'wcj-profiles-css', plugin_dir_url( __FILE__ ) . 'public/css/users-job-profiles-public.css', array(), $this->version, 'all' );

			// Enqueue JS.
			wp_enqueue_script( 'wcj-profiles-js', plugin_dir_url( __FILE__ ) . 'public/js/users-job-profiles-public.js', array( 'jquery' ), $this->version, true );

			// Pass Ajax Url to users-job-profiles-public.js
			wp_localize_script(
				'wcj-profiles-js',
				'ajax_object',
				array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'nonce'    => wp_create_nonce( 'search_nonce' ),
				)
			);
		}
	}

	// Instantiate the main class.
	new Users_Job_Profiles();

endif;
