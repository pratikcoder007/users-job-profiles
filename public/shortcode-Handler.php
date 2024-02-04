<?php
class WCJ_Profiles_Shortcode
{
    public function __construct()
    {
        add_shortcode('wcj_profiles', array($this, 'render_shortcode'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
    }

    public function render_shortcode($atts)
    {
        ob_start();

        $atts = shortcode_atts(
            array(
                'orderby' => 'date', // Default order by date
                'order'   => 'DESC', // Default order descending
            ),
            $atts,
            'wcj_profiles'
        );

        // Query to retrieve profile posts
        $args = array(
            'post_type'      => 'profile',
            'posts_per_page' => -1, // Retrieve all posts
            'orderby'        => $atts['orderby'],
            'order'          => $atts['order'],
        );

        $profiles = new WP_Query($args);

        if ($profiles->have_posts()) :
            echo '<table class="wcj-profiles-table">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>NO</th>';
            echo '<th>Name</th>';
            echo '<th>Date of Birth</th>';
            echo '<th>Hobbies</th>';
            echo '<th>Interests</th>';
            echo '<th>Years of Experience</th>';
            echo '<th>Ratings</th>';
            echo '<th>No Jobs Completed</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            $count = 1;
            while ($profiles->have_posts()) :
                $profiles->the_post();
                echo '<tr>';
                echo '<td>' . $count . '</td>';
                echo '<td>' . get_the_title() . '</td>';
                echo '<td>' . esc_html(get_post_meta(get_the_ID(), '_wcj_dob', true)) . '</td>';
                echo '<td>' . esc_html(get_post_meta(get_the_ID(), '_wcj_hobbies', true)) . '</td>';
                echo '<td>' . esc_html(get_post_meta(get_the_ID(), '_wcj_interests', true)) . '</td>';
                echo '<td>' . esc_html(get_post_meta(get_the_ID(), '_wcj_experience', true)) . '</td>';
                echo '<td>' . esc_html(get_post_meta(get_the_ID(), '_wcj_ratings', true)) . '</td>';
                echo '<td>' . esc_html(get_post_meta(get_the_ID(), '_wcj_jobs_completed', true)) . '</td>';
                echo '</tr>';
                $count++;
            endwhile;
            echo '</tbody>';
            echo '</table>';
            wp_reset_postdata();
        else :
            echo 'No profiles found.';
        endif;

        return ob_get_clean();
    }

    public function enqueue_assets()
    {
        wp_enqueue_style('wcj-profiles-css', plugin_dir_url(__FILE__) . 'assets/styles.css', array(), '1.0.0', 'all');
        wp_enqueue_script('wcj-profiles-js', plugin_dir_url(__FILE__) . 'assets/script.js', array('jquery'), '1.0.0', true);
    }
}
