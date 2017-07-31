$ = jQuery;
var initProducts = $('ul.products').html();

$(document).ready(function() {
  console.log('test');

  $('*[name="product_cat"]').change(function(e) {
    e.preventDefault();

    if ($(this).val() !== '') {

      $.ajax({
        type: 'get',
        dataType: 'html',
        url: myAjax.ajaxurl,
        data: {
          post_type: 'product',
          action: 'ASWQ',
          product_cat: $(this).val()
        },
        success: function(data) {
          console.log(data);
          $('ul.products').empty();
          $('ul.products').html(data);
        }
      });

    } else {
      $('ul.products').html(initProducts);
    }

  });

});
