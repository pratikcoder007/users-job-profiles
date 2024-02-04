<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class WCJ_Profiles
{
    /**
     * WCJ_Profiles Constructor.
     *
     * @version 1.0.0
     * @since   1.0.0
     * @access  public
     */
    function __construct()
    {
        // Hook into the init action to register the custom post type
        add_action('init', array($this, 'register_profile_post_type'));
        add_action('init', array($this, 'register_profile_taxonomies'));
    }

    /**
     * Register the 'profile' custom post type.
     *
     * @since 1.0.0
     */
    public function register_profile_post_type()
    {
        $labels = array(
            'name'               => _x('Profiles', 'post type general name', 'users-job-profiles'),
            'singular_name'      => _x('Profile', 'post type singular name', 'users-job-profiles'),
            'menu_name'          => _x('Profiles', 'admin menu', 'users-job-profiles'),
            'name_admin_bar'     => _x('Profile', 'add new on admin bar', 'users-job-profiles'),
            'add_new'            => _x('Add New', 'profile', 'users-job-profiles'),
            'add_new_item'       => __('Add New Profile', 'users-job-profiles'),
            'new_item'           => __('New Profile', 'users-job-profiles'),
            'edit_item'          => __('Edit Profile', 'users-job-profiles'),
            'view_item'          => __('View Profile', 'users-job-profiles'),
            'all_items'          => __('All Profiles', 'users-job-profiles'),
            'search_items'       => __('Search Profiles', 'users-job-profiles'),
            'parent_item_colon'  => __('Parent Profiles:', 'users-job-profiles'),
            'not_found'          => __('No profiles found.', 'users-job-profiles'),
            'not_found_in_trash' => __('No profiles found in Trash.', 'users-job-profiles'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'profile'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array('title', 'editor', 'thumbnail', 'custom-fields'),
        );

        register_post_type('profile', $args);
    }

    /**
     * Register taxonomies for the 'profile' custom post type.
     *
     * @since 1.0.0
     */
    public function register_profile_taxonomies()
    {
        // Register 'Skills' taxonomy
        $labels_skills = array(
            'name'              => _x('Skills', 'taxonomy general name', 'users-job-profiles'),
            'singular_name'     => _x('Skill', 'taxonomy singular name', 'users-job-profiles'),
            'search_items'      => __('Search Skills', 'users-job-profiles'),
            'all_items'         => __('All Skills', 'users-job-profiles'),
            'parent_item'       => __('Parent Skill', 'users-job-profiles'),
            'parent_item_colon' => __('Parent Skill:', 'users-job-profiles'),
            'edit_item'         => __('Edit Skill', 'users-job-profiles'),
            'update_item'       => __('Update Skill', 'users-job-profiles'),
            'add_new_item'      => __('Add New Skill', 'users-job-profiles'),
            'new_item_name'     => __('New Skill Name', 'users-job-profiles'),
            'menu_name'         => __('Skills', 'users-job-profiles'),
        );

        $args_skills = array(
            'hierarchical'      => true,
            'labels'            => $labels_skills,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'skills'),
        );

        register_taxonomy('skills', 'profile', $args_skills);

        // Register 'Education' taxonomy
        $labels_education = array(
            'name'              => _x('Education', 'taxonomy general name', 'users-job-profiles'),
            'singular_name'     => _x('Education', 'taxonomy singular name', 'users-job-profiles'),
            'search_items'      => __('Search Education', 'users-job-profiles'),
            'all_items'         => __('All Education', 'users-job-profiles'),
            'parent_item'       => __('Parent Education', 'users-job-profiles'),
            'parent_item_colon' => __('Parent Education:', 'users-job-profiles'),
            'edit_item'         => __('Edit Education', 'users-job-profiles'),
            'update_item'       => __('Update Education', 'users-job-profiles'),
            'add_new_item'      => __('Add New Education', 'users-job-profiles'),
            'new_item_name'     => __('New Education Name', 'users-job-profiles'),
            'menu_name'         => __('Education', 'users-job-profiles'),
        );

        $args_education = array(
            'hierarchical'      => true,
            'labels'            => $labels_education,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'education'),
        );

        register_taxonomy('education', 'profile', $args_education);
    }

}

// Instantiate the class to register the custom post type
new WCJ_Profiles();
