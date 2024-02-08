<?php
/**
 * Users Job Profiles - Profile post metaboxes
 *
 * @version 1.0.3
 * @since   1.0.0
 * @author  Pratik.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WCJ_Profile_Metaboxes' ) ) :

class WCJ_Profile_Metaboxes {

	/**
	 * Constructor.
	 *
	 * @version 1.0.3
	 * @since   1.0.0
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_profile_metabox' ) );
		add_action( 'save_post', array( $this, 'save_profile_metabox' ) );
	}

	/**
	 * Custom Metabox create for Post.
	 *
	 * @version 1.0.3
	 * @since   1.0.0
	 */
	public function add_profile_metabox() {
		add_meta_box(
			'wcj_profile_metabox',
			__( 'Profile Details', 'users-job-profiles' ),
			array( $this, 'render_profile_metabox' ),
			'profile',
			'normal',
			'high'
		);
	}

	/**
	 * Create Metabox fields.
	 *
	 * @version 1.0.3
	 * @since   1.0.0
	 */
	public function render_profile_metabox( $post ) {
		
		$dob            = get_post_meta( $post->ID, '_wcj_dob', true );
		$hobbies        = get_post_meta( $post->ID, '_wcj_hobbies', true );
		$interests      = get_post_meta( $post->ID, '_wcj_interests', true );
		$experience     = get_post_meta( $post->ID, '_wcj_experience', true );
		$ratings        = get_post_meta( $post->ID, '_wcj_ratings', true );
		$jobs_completed = get_post_meta( $post->ID, '_wcj_jobs_completed', true );
		$age            = get_post_meta( $post->ID, '_wcj_age', true );
		?>

		<table class="form-table">
			<tr>
				<th scope="row"><label for="wcj_dob"><?php _e( 'Date of Birth:', 'users-job-profiles' ); ?></label></th>
				<td><input type="date" name="wcj_dob" id="wcj_dob" value="<?php echo esc_attr( $dob ); ?>"></td>
			</tr>
			<tr>
				<th scope="row"><label for="wcj_hobbies"><?php _e( 'Hobbies:', 'users-job-profiles' ); ?></label></th>
				<td><input type="text" name="wcj_hobbies" id="wcj_hobbies" value="<?php echo esc_attr( $hobbies ); ?>"></td>
			</tr>
			<tr>
				<th scope="row"><label for="wcj_interests"><?php _e( 'Interests:', 'users-job-profiles' ); ?></label></th>
				<td><input type="text" name="wcj_interests" id="wcj_interests" value="<?php echo esc_attr( $interests ); ?>"></td>
			</tr>
			<tr>
				<th scope="row"><label for="wcj_experience"><?php _e( 'Years of Experience:', 'users-job-profiles' ); ?></label></th>
				<td><input type="number" min="0" max="100" name="wcj_experience" id="wcj_experience" value="<?php echo esc_attr( $experience ); ?>"></td>
			</tr>
			<tr>
				<th scope="row"><label for="wcj_ratings"><?php _e( 'Ratings:', 'users-job-profiles' ); ?></label></th>
				<td><input type="number" min="0" max="5" name="wcj_ratings" id="wcj_ratings" value="<?php echo esc_attr( $ratings ); ?>"></td>
			</tr>
			<tr>
				<th scope="row"><label for="wcj_jobs_completed"><?php _e( 'No Jobs Completed:', 'users-job-profiles' ); ?></label></th>
				<td><input type="number" min="0" max="100" name="wcj_jobs_completed" id="wcj_jobs_completed" value="<?php echo esc_attr( $jobs_completed ); ?>"></td>
			</tr>
			<tr>
				<th scope="row"><label for="wcj_age"><?php _e( 'Age', 'users-job-profiles' ); ?></label></th>
				<td><input type="number" min="0" max="100" name="wcj_age" id="wcj_age" value="<?php echo esc_attr( $age ); ?>"></td>
			</tr>
		</table>
		<?php
	}

	/**
	 * Save meta Value on Post.
	 *
	 * @version 1.0.3
	 * @since   1.0.0
	 */
	public function save_profile_metabox( $post_id ) {
		$wcj_dob            = isset( $_POST['wcj_dob'] ) ? sanitize_text_field( $_POST['wcj_dob'] ) : '';
		$wcj_hobbies        = isset( $_POST['wcj_hobbies'] ) ? sanitize_text_field( $_POST['wcj_hobbies'] ) : '';
		$wcj_interests      = isset( $_POST['wcj_interests'] ) ? sanitize_text_field( $_POST['wcj_interests'] ) : '';
		$wcj_experience     = isset( $_POST['wcj_experience'] ) ? absint( $_POST['wcj_experience'] ) : 0;
		$wcj_ratings        = isset( $_POST['wcj_ratings'] ) ? absint( $_POST['wcj_ratings'] ) : 0;
		$wcj_jobs_completed = isset( $_POST['wcj_jobs_completed'] ) ? absint( $_POST['wcj_jobs_completed'] ) : 0;
		$wcj_age            = isset( $_POST['wcj_age'] ) ? absint( $_POST['wcj_age'] ) : 0;

		// Update post meta.
		update_post_meta( $post_id, '_wcj_dob', $wcj_dob );
		update_post_meta( $post_id, '_wcj_hobbies', $wcj_hobbies );
		update_post_meta( $post_id, '_wcj_interests', $wcj_interests );
		update_post_meta( $post_id, '_wcj_experience', $wcj_experience );
		update_post_meta( $post_id, '_wcj_ratings', $wcj_ratings );
		update_post_meta( $post_id, '_wcj_jobs_completed', $wcj_jobs_completed );
		update_post_meta( $post_id, '_wcj_age', $wcj_age );

	}
}

endif;

// Instantiate the class.
new WCJ_Profile_Metaboxes();
