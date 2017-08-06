$ = jQuery;
// Get the all products before filter
var initProducts = $('.asw_wrap').html();
var regularPriceMin = 0;
var regularPriceMax = 0;

$('a.page-numbers').css( 'cursor', 'pointer' );


$(document).ready(function() {

  // Disabled submit ordering then when user access first time the shop page
  $('form.woocommerce-ordering').submit(function(event){
        event.preventDefault();
    });

  // Build select2
  $('*[name="product_cat"]').select2();

  function ASW_AJAX(e) {

    e.preventDefault();

    // Check if the select is not empty
    if ($('*[name="product_cat"]').val() !== '' || $('*[name="orderby"]').val() !== '') {

      orderby = $('*[name="orderby"]').val();

      $.ajax({
        type: 'post',
        dataType: 'html',
        url: myAjax.ajaxurl,
        data: {
          nonce: $('*[name="asw_nonce"]').val(),
          post_type: 'product',
          action: 'ASWQ',
          product_cat: $('*[name="product_cat"]').val(),
          orderby: orderby,
          sku: $('*[name="sku"]').val(),
          reqular_price_min: regularPriceMin,
          reqular_price_max: regularPriceMax
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

  }

  // Make a AJAX request if the select of category is changed or orderby is changed
  $('body').on('change', '*[name="product_cat"], *[name="orderby"]', ASW_AJAX);

  // Make a AJAX reqest if SKU input is completed
  $('body').on('input', '*[name="sku"]', ASW_AJAX);

  // Make a AJAX reqest if range price is changed
  $('body').on('slidechange', '#slider-range', ASW_AJAX);

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
        orderby: orderby,
        sku: $('*[name="sku"]').val(),
        nonce: $('*[name="asw_nonce"]').val()
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

function rangePrice() {

  $('#slider-range').slider({

    range: true,
    // values: [ 75, 300 ], // Create in admin 2 fields
    min: Math.round(myAjax.minPrice),
    max: Math.round(myAjax.maxPrice),
    step: 1,
    slide: function(event, ui) {
      regularPriceMin = ui.values[0];
      regularPriceMax = ui.values[1];
    }

  });

  console.log($( '#slider-range' ).slider( 'option', 'min'), $( '#slider-range' ).slider( 'option', 'max'));

}

rangePrice();
