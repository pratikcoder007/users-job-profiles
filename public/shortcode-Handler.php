<?php
class WCJ_Profiles_Shortcode
{
    public function __construct()
    {
        add_shortcode('wcj_profiles', array($this, 'render_shortcode'));
        add_shortcode('wcj_filter_form', array($this, 'Wcj_filter_shortcode'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public function Wcj_filter_shortcode() {
        ob_start();
        ?>
        <section class="form_section pt-5 pb-5">
          <div class="container">
            <div class="card">
              <div class="card-body">      
                <form>
                  <div class="form-row">
                    <div class="form-group col-md-12">
                      <label for="Keyword">Keyword</label>
                      <input type="email" class="form-control" id="Keyword">
                    </div>
                    <div class="form-group col-md-6">
                      <label for="SelectSkills">Skills</label>
                        <?php
                            $skills = get_terms(array(
                              'taxonomy' => 'skills',
                              'hide_empty' => false,
                            ));
                        ?>
                      <select id="SelectSkills" name="Skills[]" class="js-states form-control" multiple>
                    <?php
                      foreach ($skills as $skill) {
                        echo '<option value="' . esc_attr($skill->slug) . '">' . esc_html($skill->name) . '</option>';
                      }
                    ?>
                  </select>
                    </div>
                    <div class="form-group col-md-6">
                      <label for="SelectEducation">Education</label>
                      <?php
                            $educations  = get_terms(array(
                              'taxonomy' => 'education',
                              'hide_empty' => false,
                            ));
                        ?>
                      <select id="SelectEducation" name="Education[]" class="js-states form-control" multiple>
                    <?php
                      foreach ($educations as $education) {
                        echo '<option value="' . esc_attr($education->slug) . '">' . esc_html($education->name) . '</option>';
                      }
                    ?>
                  </select>
                    </div>
                    <div class="range-form form-group col-md-6">
                      <label for="formControlRange">Age</label>
                      <input type="range" min="1" max="100" value="1" class="form-control-range range-slider" id="AgeRange">
                      <div class="">
                        <span id="AgeNumber">0</span>
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
                        <input type="hidden" name="whatever3" class="rating-value" value="0">
                      </div>
                    </div>
                    <div class="form-group col-md-6">
                      <label for="JobsComplated">No of jobs complated</label>
                      <input type="number" class="form-control" id="JobsComplated">
                    </div>
                    <div class="form-group col-md-6">
                      <label for="NoOfExperience">Years of experience</label>
                      <input type="number" class="form-control" id="NoOfExperience">
                    </div>
                  </div>
                  <div class="d-flex flex-wrap justify-content-center">
                    <div class="form-group col-md-2">
                      <button type="submit" class="btn btn-primary btn-lg btn-block">Search</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </section>
        <?php
        return ob_get_clean();
    }

    public function render_shortcode($atts)
    {
        ob_start();

        $atts = shortcode_atts(
            array(
                'orderby' => 'date',
                'order'   => 'DESC',
                'paged'   => get_query_var('paged') ? get_query_var('paged') : 1,
            ),
            $atts,
            'wcj_profiles'
        );

        // Query to retrieve profile posts
        $args = array(
            'post_type'      => 'profile',
            'posts_per_page' => 5,
            'paged'          => $atts['paged'],
            'orderby'        => $atts['orderby'],
            'order'          => $atts['order'],
        );

        

        $profiles = new WP_Query($args);

        if ($profiles->have_posts()) :
            echo '<table class="wcj-profiles-table">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>No</th>';
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
            $count = (($atts['paged'] - 1) * 5) + 1;
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

            // Add pagination links
            echo '<div class="wcj-profiles-pagination">';
            echo paginate_links(array(
                'base'      => get_pagenum_link(1) . '%_%',
                'format'    => '?paged=%#%',
                'current'   => max(1, $atts['paged']),
                'total'     => $profiles->max_num_pages,
                'prev_text' => '&laquo;',
                'next_text' => '&raquo;',
            ));
            echo '</div>';

            wp_reset_postdata();
        else :
            echo 'No profiles found.';
        endif;

        return ob_get_clean();
    }

    public function enqueue_scripts() {
        wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css', array(), null);
        wp_enqueue_style('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css', array(), null);
        wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css', array(), null);

        wp_enqueue_script('jquery-slim', 'https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js', array('jquery'), null, true);
        wp_enqueue_script('popper', 'https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js', array('jquery'), null, true);
        wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js', array('jquery', 'popper'), null, true);
        wp_enqueue_script('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js', array('jquery'), null, true);
    }
}
