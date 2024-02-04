<?php
class WCJ_Profile_Metaboxes
{
    public function __construct()
    {
        add_action('add_meta_boxes', array($this, 'add_profile_metabox'));
        add_action('save_post', array($this, 'save_profile_metabox'));
    }

    public function add_profile_metabox()
    {
        add_meta_box(
            'wcj_profile_metabox',
            __('Profile Details', 'users-job-profiles'),
            array($this, 'render_profile_metabox'),
            'profile',
            'normal',
            'high'
        );
    }

    public function render_profile_metabox($post)
    {
        // Retrieve existing values
        $dob = get_post_meta($post->ID, '_wcj_dob', true);
        $hobbies = get_post_meta($post->ID, '_wcj_hobbies', true);
        $interests = get_post_meta($post->ID, '_wcj_interests', true);
        $experience = get_post_meta($post->ID, '_wcj_experience', true);
        $ratings = get_post_meta($post->ID, '_wcj_ratings', true);
        $jobs_completed = get_post_meta($post->ID, '_wcj_jobs_completed', true);
        $age = get_post_meta($post->ID, '_wcj_age', true);


        // Output form fields in a table layout
        ?>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="wcj_dob"><?php _e('Date of Birth:', 'users-job-profiles'); ?></label></th>
                <td><input type="date" name="wcj_dob" id="wcj_dob" value="<?php echo esc_attr($dob); ?>"></td>
            </tr>
            <tr>
                <th scope="row"><label for="wcj_hobbies"><?php _e('Hobbies:', 'users-job-profiles'); ?></label></th>
                <td><input type="text" name="wcj_hobbies" id="wcj_hobbies" value="<?php echo esc_attr($hobbies); ?>"></td>
            </tr>
            <tr>
                <th scope="row"><label for="wcj_interests"><?php _e('Interests:', 'users-job-profiles'); ?></label></th>
                <td><input type="text" name="wcj_interests" id="wcj_interests" value="<?php echo esc_attr($interests); ?>"></td>
            </tr>
            <tr>
                <th scope="row"><label for="wcj_experience"><?php _e('Years of Experience:', 'users-job-profiles'); ?></label></th>
                <td><input type="number" name="wcj_experience" id="wcj_experience" value="<?php echo esc_attr($experience); ?>"></td>
            </tr>
            <tr>
                <th scope="row"><label for="wcj_ratings"><?php _e('Ratings:', 'users-job-profiles'); ?></label></th>
                <td><input type="number" name="wcj_ratings" id="wcj_ratings" value="<?php echo esc_attr($ratings); ?>"></td>
            </tr>
            <tr>
                <th scope="row"><label for="wcj_jobs_completed"><?php _e('No Jobs Completed:', 'users-job-profiles'); ?></label></th>
                <td><input type="number" name="wcj_jobs_completed" id="wcj_jobs_completed" value="<?php echo esc_attr($jobs_completed); ?>"></td>
            </tr>
            <tr>
                <th scope="row"><label for="wcj_age"><?php _e('Age', 'users-job-profiles'); ?></label></th>
                <td><input type="number" name="wcj_age" id="wcj_age" value="<?php echo esc_attr($age); ?>"></td>
            </tr>
        </table>
        <?php
    }

    public function save_profile_metabox($post_id)
    {
        // Save the meta data
        update_post_meta($post_id, '_wcj_dob', sanitize_text_field($_POST['wcj_dob']));
        update_post_meta($post_id, '_wcj_hobbies', sanitize_text_field($_POST['wcj_hobbies']));
        update_post_meta($post_id, '_wcj_interests', sanitize_text_field($_POST['wcj_interests']));
        update_post_meta($post_id, '_wcj_experience', absint($_POST['wcj_experience']));
        update_post_meta($post_id, '_wcj_ratings', absint($_POST['wcj_ratings']));
        update_post_meta($post_id, '_wcj_jobs_completed', absint($_POST['wcj_jobs_completed']));
        update_post_meta($post_id, '_wcj_age', absint($_POST['wcj_age']));

    }
}

// Instantiate the class
new WCJ_Profile_Metaboxes();
