$ = jQuery;

$(document).ready(function() {
  console.log('test');

  $('*[name="product_cat"]').change(function(e) {
    e.preventDefault();

    if ($(this).val() !== '') {

      $.ajax({
        type: 'post',
        // dataType: 'json',
        url: myAjax.ajaxurl,
        data: {
          action: 'ASWQ',
          product_cat: $(this).val()
        }
      });

    }

  });

});
