<?php
/*
 * Plugin Name:       Users Job Profiles
 * Description:       This plugin is used to create a profile listing page with search and sorting functionality. To display on the frontend, please use this shortcode:
 * Version:           1.0.0
 * Author:            Pratik
 * Text Domain:       users-job-profiles
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('Users_Job_Profiles')) :

    /**
     * Main Users_Job_Profiles Class
     *
     * @class   Users_Job_Profiles
     * @version 1.0.0
     * @since   1.0.0
     */
    class Users_Job_Profiles
    {
        private $version;

        /**
         * Users_Job_Profiles Constructor.
         *
         * @version 1.0.0
         * @since   1.0.0
         * @access  public
         */
        public function __construct()
        {
            $this->version = get_file_data(__FILE__, array('Version' => 'Version'), false);

            // Include other necessary files
            require_once('admin/mp-profiles.php');
            require_once('admin/metaboxes.php');
            require_once('public/shortcode-Handler.php');


            // Instantiate the class to register the custom post type
        new WCJ_Profiles();

        // Instantiate the shortcode class
        new WCJ_Profiles_Shortcode();

            // Enqueue assets
            add_action('admin_enqueue_scripts', array($this, 'enqueue_assets'));
        }

        /**
         * Enqueue CSS and JS assets.
         */
        public function enqueue_assets()
        {
            // Enqueue CSS
            wp_enqueue_style('wcj-profiles-css', plugin_dir_url(__FILE__) . 'assets/styles.css', array(), $this->version, 'all');

            // Enqueue JS
            wp_enqueue_script('wcj-profiles-js', plugin_dir_url(__FILE__) . 'assets/script.js', array('jquery'), $this->version, true);
        }
    }

    // Instantiate the main class
    new Users_Job_Profiles();

endif;
