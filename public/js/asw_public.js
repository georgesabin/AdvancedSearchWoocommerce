$ = jQuery;
// Get the all products before filter
var initProducts = $('.asw_wrap').html();
$('a.page-numbers').css( 'cursor', 'pointer' );

$(document).ready(function() {

  // Disabled submit ordering then when user access first time the shop page
  $('form.woocommerce-ordering').submit(function(event){
        event.preventDefault();
    });

  // Make a AJAX request if the select of category is changed
  $('body').on('change', '*[name="product_cat"], *[name="orderby"]', function(e) {

    e.preventDefault();

    // Check if the select is not empty
    if ($('*[name="product_cat"]').val() !== '' || $('*[name="orderby"]').val() !== '') {

      orderby = $('*[name="orderby"]').val();

      $.ajax({
        type: 'post',
        dataType: 'html',
        url: myAjax.ajaxurl,
        data: {
          post_type: 'product',
          action: 'ASWQ',
          product_cat: $('*[name="product_cat"]').val(),
          orderby: orderby
        },
        success: function(data) {

          // Empty the wrap
          $('.asw_wrap').empty();
          // Modify html with the new data
          $('.asw_wrap').html(data);
          // Remove href from paginationAJAX
          $('a.page-numbers').removeAttr('href');
          $('a.page-numbers').css( 'cursor', 'pointer' );
          $('*[name="orderby"] option[value=' + orderby + ']').attr('selected', 'selected');

        }
      });

    } else { $('.asw_wrap').html(initProducts); }

  });

  // By default. Remove href and run request ajax for pagination
  $('a.page-numbers').removeAttr('href');
  paginationAJAX();

});

// Created a recursive function for pagination
function paginationAJAX() {

  $('body').on('click', 'li a.page-numbers', function() {
    orderby = $('*[name="orderby"]').val();
    $.ajax({
      type: 'post',
      dataType: 'html',
      url: myAjax.ajaxurl,
      data: {
        post_type: 'product',
        action: 'ASWQ',
        product_cat: $('*[name="product_cat"]').val(),
        paged: $(this).html(),
        orderby: orderby
      },
      success: function(data) {
        $('.asw_wrap').empty();
        $('.asw_wrap').html(data);
        $('a.page-numbers').css( 'cursor', 'pointer' );
        $('a.page-numbers').removeAttr('href');
        $('*[name="orderby"] option[value=' + orderby + ']').attr('selected', 'selected');
      }
    });
  });

}
