<?php
/**
 * Users Job Profiles - Shortcodes
 *
 * @version 1.0.3
 * @since   1.0.0
 * @author  pratik.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WCJ_Profiles_Shortcode' ) ) :

class WCJ_Profiles_Shortcode {


	/**
	 * Constructor.
	 *
	 * @version 1.0.3
	 * @since   1.0.0
	 */
	public function __construct() {

		// Shortcode.
		add_shortcode( 'wcj_profiles', array( $this, 'render_shortcode' ) );

		// Scripts.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Ajax.
		add_action( 'wp_ajax_wcj_ajax_search', array( $this, 'Wcj_ajax_search' ) );
		add_action( 'wp_ajax_nopriv_wcj_ajax_search', array( $this, 'Wcj_ajax_search' ) );
	}

	/**
	 * Custom fuction to display taxonomy droupdown.
	 *
	 * @version 1.0.3
	 * @since   1.0.0
	 */
	public function display_taxonomy_dropdown( $taxonomy, $name ) {
		$selected_terms = isset( $_GET[ $name ] ) ? $_GET[ $name ] : array();
		$terms          = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
			)
		);
		?>
	<select name="<?php echo esc_attr( $name ); ?>[]" class="js-states form-control" multiple>
		<?php
		foreach ( $terms as $term ) {
			$selected = in_array( $term->slug, $selected_terms ) ? 'selected' : '';
			echo '<option value="' . esc_attr( $term->slug ) . '" ' . esc_attr( $selected ) . '>' . esc_html( $term->name ) . '</option>';
		}
		?>
	</select>
		<?php
	}

	/**
	 * Shortcode to display Profile listing and filters.
	 *
	 * @version 1.0.3
	 * @since   1.0.0
	 */
	public function render_shortcode( $atts ) {
		ob_start();
		$orderby = isset( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : 'title';
		$order   = isset( $_GET['order'] ) ? sanitize_text_field( $_GET['order'] ) : 'ASC';
		$paged   = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
		?>
	
	<section class="form_section pt-5 pb-5">
		<div class="container">
		  <div class="card">
				<div class="card-body">
				  <form method="get" id="ajax-search-form">
						<div class="form-row">
						  <div class="form-group col-md-12">
								<label for="Keyword">Keyword</label>
								<input type="text" value="<?php echo esc_attr( isset( $_GET['Keyword'] ) ? $_GET['Keyword'] : '' ); ?>" name="Keyword" class="form-control" id="Keyword">
						  </div>
						  <div class="form-group col-md-6">
								<label for="SelectSkills">Skills</label>
								<?php $this->display_taxonomy_dropdown( 'skills', 'Skills' ); ?>
						  </div>
						  <div class="form-group col-md-6">
								<label for="SelectEducation">Education</label>
								<?php $this->display_taxonomy_dropdown( 'education', 'Education' ); ?>
						  </div>
						  <div class="range-form form-group col-md-6">
								<label for="formControlRange">Age</label>
								<?php
								$default_range_value = 0;
								$range_value         = isset( $_GET['range'] ) ? $_GET['range'] : $default_range_value;
								?>
								<input type="range" name="range" min="0" max="100" value="<?php echo esc_attr( $range_value ); ?>" class="form-control-range range-slider" id="AgeRange">
								<div class="">
								  <span id="AgeNumber"><?php echo esc_attr( $range_value ); ?></span>
								</div>
						  </div>
						  <div class="range-form form-group col-md-6">
								<label for="SelectRating">Rating</label>
								<div class="star-rating">
								  <span class="fa fa-star-o" data-rating="1"></span>
								  <span class="fa fa-star-o" data-rating="2"></span>
								  <span class="fa fa-star-o" data-rating="3"></span>
								  <span class="fa fa-star-o" data-rating="4"></span>
								  <span class="fa fa-star-o" data-rating="5"></span>
								<input type="hidden" name="rating" class="rating-value" value="<?php echo esc_attr( isset( $_GET['rating'] ) ? $_GET['rating'] : '' ); ?>">
							</div>
						  </div>
						  <div class="form-group col-md-6">
								<label for="JobsCompleted">No of jobs completed</label>
								<input type="number" name="JobsCompleted" value="<?php echo esc_attr( isset( $_GET['JobsCompleted'] ) ? $_GET['JobsCompleted'] : '' ); ?>" class="form-control" id="JobsCompleted">
						  </div>
						  <div class="form-group col-md-6">
								<label for="NoOfExperience">Years of experience</label>
								<input type="number" name="NoOfExperience" value="<?php echo esc_attr( isset( $_GET['NoOfExperience'] ) ? $_GET['NoOfExperience'] : '' ); ?>" class="form-control" id="NoOfExperience">
						  </div>
						</div>
						<div class="d-flex flex-wrap justify-content-center">
						  <div class="form-group col-md-2">
							<button type="submit" id="ajax-search-button" class="btn btn-primary btn-lg btn-block">Search</button>
						  </div>
						</div>
				  </form>
				</div>
		  </div>
		</div>
  </section>
		<?php
		// Query to retrieve profile posts.
		if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
			$args = array(
				'post_type'      => 'profile',
				'posts_per_page' => 5,
				'paged'          => $paged,
				'orderby'        => $orderby,
				'order'          => $order,
			);

			// Skills filter.
			if ( isset( $_GET['Skills'] ) && is_array( $_GET['Skills'] ) ) {
				$args['tax_query'][] = array(
					'taxonomy' => 'skills',
					'field'    => 'slug',
					'terms'    => $_GET['Skills'],
				);
			}

			// Education filter.
			if ( isset( $_GET['Education'] ) && is_array( $_GET['Education'] ) ) {
				$args['tax_query'][] = array(
					'taxonomy' => 'education',
					'field'    => 'slug',
					'terms'    => $_GET['Education'],
				);
			}

			// Age filter.
			if ( isset( $_GET['range'] ) && ! empty( $_GET['range'] ) ) {
				$args['meta_query'][] = array(
					'key'     => '_wcj_age',
					'value'   => sanitize_text_field( $_GET['range'] ),
					'type'    => 'NUMERIC',
					'compare' => '<=',
				);
			}

			// Rating filter.
			if ( isset( $_GET['rating'] ) && ! empty( $_GET['rating'] ) ) {
				$args['meta_query'][] = array(
					'key'     => '_wcj_ratings',
					'value'   => sanitize_text_field( $_GET['rating'] ),
					'type'    => 'NUMERIC',
					'compare' => '=',
				);
			}

			// Jobs Completed filter.
			if ( isset( $_GET['JobsComplated'] ) && ! empty( $_GET['JobsComplated'] ) ) {
				$args['meta_query'][] = array(
					'key'     => '_wcj_jobs_completed',
					'value'   => sanitize_text_field( $_GET['JobsComplated'] ),
					'type'    => 'NUMERIC',
					'compare' => '=',
				);
			}

			// Years of Experience filter.
			if ( isset( $_GET['NoOfExperience'] ) && ! empty( $_GET['NoOfExperience'] ) ) {
				$args['meta_query'][] = array(
					'key'     => '_wcj_experience',
					'value'   => sanitize_text_field( $_GET['NoOfExperience'] ),
					'type'    => 'NUMERIC',
					'compare' => '=',
				);
			}
		} else {
			$args = array(
				'post_type'      => 'profile',
				'posts_per_page' => 5,
				'paged'          => $paged,
				'orderby'        => $orderby,
				'order'          => $order,
			);
		}

		$profiles = new WP_Query( $args );

		if ( $profiles->have_posts() ) { 
			echo '<table class="wcj-profiles-table">';
			echo '<thead>';
			echo '<tr>';
			echo '<th>No</th>';
			echo '<th><a href="' . esc_url(
				    add_query_arg(
				        array(
				            'orderby' => 'title',
				            'order'   => ( $orderby === 'title' && $order === 'ASC' ) ? 'DESC' : 'ASC',
				        )
				    )
					) . '">
				    Post Name
				    <span class="sorting-indicators ' . ($orderby === 'title' ? ($order === 'ASC' ? 'asc' : 'desc') : '') . '">
				        <span class="sorting-indicator icon-arrow-up" aria-hidden="true"></span>
				        <span class="sorting-indicator icon-arrow-down" aria-hidden="true"></span>
				    </span>
				</a></th>';
			echo '<th>Date of Birth</th>';
			echo '<th>Hobbies</th>';
			echo '<th>Interests</th>';
			echo '<th>Years of Experience</th>';
			echo '<th>Ratings</th>';
			echo '<th>No Jobs Completed</th>';
			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';

			$count = ( ( $paged - 1 ) * 5 ) + 1;
			while ( $profiles->have_posts() ) :
				$profiles->the_post();
				echo '<tr>';
				echo '<td>' . $count . '</td>';
				echo '<td><a href="' . esc_url( get_permalink() ) . '">' . get_the_title() . '</a></td>';
				echo '<td>' . esc_html( get_post_meta( get_the_ID(), '_wcj_dob', true ) ) . '</td>';
				echo '<td>' . esc_html( get_post_meta( get_the_ID(), '_wcj_hobbies', true ) ) . '</td>';
				echo '<td>' . esc_html( get_post_meta( get_the_ID(), '_wcj_interests', true ) ) . '</td>';
				echo '<td>' . esc_html( get_post_meta( get_the_ID(), '_wcj_experience', true ) ) . '</td>';
				$rating = esc_html( get_post_meta( get_the_ID(), '_wcj_ratings', true ) );
				echo '<td>';
				for ( $i = 1; $i <= 5; $i++ ) {
					echo ( $i <= $rating ) ? '★' : '☆';
				}
				echo '</td>';
				echo '<td>' . esc_html( get_post_meta( get_the_ID(), '_wcj_jobs_completed', true ) ) . '</td>';
				echo '</tr>';
				$count++;
		  endwhile;
			echo '</tbody>';
			echo '</table>';

			// Add pagination links.
			if ( $profiles->max_num_pages > 1 ) {
				echo '<div class="wcj-profiles-pagination">';
				echo paginate_links(
					array(
						'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
						'format'    => '?paged=%#%',
						'current'   => max( 1, $paged ),
						'total'     => $profiles->max_num_pages,
						'prev_text' => __( 'Previous', 'users-job-profiles' ),
						'next_text' => __( 'Next', 'users-job-profiles' ),
						'mid_size'  => 1,
					)
				);
			}
			echo '</div>';

			wp_reset_postdata();
	  } else {
		  echo '<div class="no-profile">No profiles found.';
	  }
	  return ob_get_clean();
	}

	/**
	 * Ajax function for search filter.
	 *
	 * @version 1.0.3
	 * @since   1.0.0
	 */
	public function wcj_ajax_search() {
		check_ajax_referer( 'search_nonce', 'nonce' );

		$keyword = sanitize_text_field( $_GET['keyword'] );
		$args    = array(
			'post_type'      => 'profile',
			'posts_per_page' => 5,
			's'              => $keyword,
		);

		$profiles = new WP_Query( $args );

		if ( $profiles->have_posts() ) {
			$count = 1;
			ob_start();
			while ( $profiles->have_posts() ) {
				$profiles->the_post();
				?>
			<tr>
				<td><?php echo $count; ?></td>
				<td><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo get_the_title(); ?></a></td>
				<td><?php echo esc_html( get_post_meta( get_the_ID(), '_wcj_dob', true ) ); ?></td>
				<td><?php echo esc_html( get_post_meta( get_the_ID(), '_wcj_hobbies', true ) ); ?></td>
				<td><?php echo esc_html( get_post_meta( get_the_ID(), '_wcj_interests', true ) ); ?></td>
				<td><?php echo esc_html( get_post_meta( get_the_ID(), '_wcj_experience', true ) ); ?></td>
				<?php
				$rating = esc_html( get_post_meta( get_the_ID(), '_wcj_ratings', true ) );
				?>
				<td>
					<?php
					for ( $i = 1; $i <= 5; $i++ ) {
						echo ( $i <= $rating ) ? '★' : '☆';
					}
					?>
				</td>
				<td><?php echo esc_html( get_post_meta( get_the_ID(), '_wcj_jobs_completed', true ) ); ?></td>
			</tr>
				<?php
				$count++;
			}
			wp_reset_postdata();
			$response = ob_get_clean();
			echo $response;
		} else {
			echo '<tr><td colspan="8">No matching profiles found.</td></tr>';
		}

		wp_die();
	}

	/**
	 * Loaded some CDN css and JS.
	 *
	 * @version 1.0.3
	 * @since   1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( 'bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css', array(), null );
		wp_enqueue_style( 'select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css', array(), null );
		wp_enqueue_style( 'font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css', array(), null );

		wp_enqueue_script( 'popper', 'https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js', array( 'jquery' ), null, true );
		wp_enqueue_script( 'bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js', array( 'jquery', 'popper' ), null, true );
		wp_enqueue_script( 'select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js', array( 'jquery' ), null, true );
	}
}

endif;

return new WCJ_Profiles_Shortcode();
