$ = jQuery;
var initProducts = $('.asw_wrap').html();

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

          $('.asw_wrap').empty();
          $('.asw_wrap').html(data);
          $('a.page-numbers').removeAttr('href');
          pagination();

        }
      });

    } else { $('.asw_wrap').html(initProducts); }

  });


});

$('a.page-numbers').removeAttr('href');
pagination();

// Created a recursive function for pagination
function pagination() {

  $('li a.page-numbers').click(function() {
    $.ajax({
      type: 'get',
      dataType: 'html',
      url: myAjax.ajaxurl,
      data: {
        post_type: 'product',
        action: 'ASWQ',
        product_cat: $('*[name=\"product_cat\"]').val(),
        paged: $(this).html()
      },
      success: function(data) {
        $('.asw_wrap').empty();
        $('.asw_wrap').html(data);
        $('a.page-numbers').removeAttr('href');
        pagination();
      }
    });
  });

}
