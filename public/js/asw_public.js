$ = jQuery;

// Get the all products before filter
var initProducts = $('.asw-wrap').html();
var regularPriceMin = 0;
var regularPriceMax = 0;

function showLoader(wrap, selector) {
  if (myAjax.disableLoader !== 'disable') {
    $(wrap).show();
    $(selector).show();
  }
}

function hideLoader(wrap, selector) {
  if (myAjax.disableLoader !== 'disable') {
    $(wrap).hide();
    $(selector).hide();
  }
}

$(document).ready(function() {

  $('a.page-numbers').css('cursor', 'pointer');

  $('#asw-filter').hide();
  $('#asw-filter-button').click(function() {
    $('#asw-filter').slideToggle(myAjax.toggleSlideTime);
  });

  // Disabled submit ordering then when user access first time the shop page
  $('form.woocommerce-ordering').submit(function(event){
        event.preventDefault();
    });

  // Build select2
  $('*[name="product_cat"]').select2({
    width: '100%'
  });
  $('*[name="stock_status"]').select2({
    width: '100%'
  });

  function ASW_AJAX(e) {

    // Set height for loader wrap
    $('#asw-wrap-loader').css('height', $('.asw-wrap').height() + 'px');

    e.preventDefault();

    // Check if the select is not empty
    // if ($(this).val() !== '') {
      orderby = $('*[name="orderby"]').val();
      console.log(orderby);

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
          stock_status: $('*[name="stock_status"]').val(),
          regular_price_min: regularPriceMin,
          regular_price_max: regularPriceMax,
          nonce: $('*[name="asw_nonce"]').val()
        },
        beforeSend: function() {
          showLoader('#asw-wrap-loader', '#asw-loader');
          console.log(1);
        },
        success: function(data) {
          // If shortcode is set, then open the modal with the request data
          if ($('*[name="is_shortcode"]').val() === 'true') {
            $('#asw-title-modal').html(myAjax.shortcodeModalTitle);
            $('.modal-body').html(data);
            $('#woo-modal').modal('show');
            hideLoader('#asw-wrap-loader', '#asw-loader');
            $('a.page-numbers').removeAttr('href');
            $('a.page-numbers').css( 'cursor', 'pointer' );
            $('*[name="orderby"] option[value=' + orderby + ']').attr('selected', 'selected');
          } else {
            // Empty the wrap
            $('.asw-wrap').empty();
            // Modify html with the new data
            $('.asw-wrap').html(data);
            hideLoader('#asw-wrap-loader', '#asw-loader');
            console.log(2);
            // Remove href from paginationAJAX
            $('a.page-numbers').removeAttr('href');
            $('a.page-numbers').css( 'cursor', 'pointer' );
            $('*[name="orderby"] option[value=' + orderby + ']').attr('selected', 'selected');
          }

        }
      });

    // } else { $('.asw-wrap').html(initProducts); hideLoader('#asw-loader'); }

  }

  // Make a AJAX request if the select of category is changed or orderby is changed
  $('body').on('change', '*[name="product_cat"], *[name="orderby"], *[name="stock_status"]', ASW_AJAX);

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
    // Set height for loader wrap
    $('#asw-wrap-loader').css('height', $('.asw-wrap').height() + 'px');
    showLoader('#asw-wrap-loader', '#asw-loader');
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
        stock_status: $('*[name="stock_status"]').val(),
        nonce: $('*[name="asw_nonce"]').val()
      },
      success: function(data) {
        // If shortcode is set, then open the modal with the request data
        if ($('*[name="is_shortcode"]').val() === 'true') {
          $('.modal-body').html(data);
          $('#woo-modal').modal('show');
          hideLoader('#asw-wrap-loader', '#asw-loader');
          $('a.page-numbers').css( 'cursor', 'pointer' );
          $('a.page-numbers').removeAttr('href');
          $('*[name="orderby"] option[value=' + orderby + ']').attr('selected', 'selected');
        } else {
          $('.asw-wrap').empty();
          $('.asw-wrap').html(data);
          hideLoader('#asw-wrap-loader', '#asw-loader');
          $('a.page-numbers').css( 'cursor', 'pointer' );
          $('a.page-numbers').removeAttr('href');
          $('*[name="orderby"] option[value=' + orderby + ']').attr('selected', 'selected');
        }
      }
    });
  });

}

function rangePrice() {

  $('#slider-range').slider({

    range: true,
    values: [ myAjax.limitMinPrice, myAjax.limitMaxPrice ], // Create in admin 2 fields
    min: Math.round(myAjax.minPrice),
    max: Math.round(myAjax.maxPrice),
    step: 1,
    slide: function(event, ui) {
      regularPriceMin = ui.values[0];
      regularPriceMax = ui.values[1];
      $('.range-price').html(myAjax.currencySymbol + $('#slider-range').slider('values', 0) + ' - ' + myAjax.currencySymbol + $('#slider-range').slider('values', 1));
    }
  });

  $('.range-price').html(myAjax.currencySymbol + $('#slider-range').slider('values', 0) + ' - ' + myAjax.currencySymbol + $('#slider-range').slider('values', 1));

}

rangePrice();
