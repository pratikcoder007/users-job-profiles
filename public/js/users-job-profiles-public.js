jQuery(document).ready(function ($) {
   
	$("#SelectSkills").select2({
	    allowClear: true
	});
	$("#SelectEducation").select2({
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
});
