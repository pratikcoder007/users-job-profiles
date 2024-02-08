/**
 * 
 * JS For the Profile List.
 */
jQuery(document).ready(function ($) {
   
  $(".js-states").select2({
      allowClear: true
  });

    var slider = document.getElementById("AgeRange");
    var output = document.getElementById("AgeNumber");
    output.innerHTML = slider.value; 
    slider.oninput = function() {
      output.innerHTML = this.value;
    }

    var $star_rating = $('.star-rating .fa');
    var SetRatingStar = function() {
    return $star_rating.each(function() {
      if (parseInt($star_rating.siblings('input.rating-value').val()) >= parseInt($(this).data('rating'))) {
        return $(this).removeClass('fa-star-o').addClass('fa-star');
      } else {
        return $(this).removeClass('fa-star').addClass('fa-star-o');
      }
    });
  };

  $star_rating.on('click', function() {
    $star_rating.siblings('input.rating-value').val($(this).data('rating'));
    return SetRatingStar();
  });

  SetRatingStar();

  // Ajax Search 
  $('#ajax-search-form').on('submit', function(e) {
    var keywordValue = $('#Keyword').val().trim();
        if (keywordValue !== '') {
            e.preventDefault();

            $.ajax({
                type: 'GET',
                url: ajax_object.ajax_url,
                data: {
                  action: 'wcj_ajax_search',
                  keyword: keywordValue,
                  nonce: ajax_object.nonce
                },
                success: function(response) {
                    $('.wcj-profiles-table tbody').html(response);
                    $('.wcj-profiles-pagination').hide();
                }
            });
        }
    });
});
