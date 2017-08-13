$ = jQuery;

$(document).ready(function() {

  // If slide checkbox is checked, then disable min and max, else activate
  $('*[name="asw_slide_regular_price"]').change(function() {
    if ($('*[name="asw_slide_regular_price"]').is(':checked')) {
      $('#asw-min-regular-price').attr('disabled', true);
      $('#asw-max-regular-price').attr('disabled', true);
    } else {
      $('#asw-min-regular-price').attr('disabled', false);
      $('#asw-max-regular-price').attr('disabled', false);
    }
  });

});
