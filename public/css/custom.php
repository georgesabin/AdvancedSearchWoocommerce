<?php
  header("Content-type: text/css; charset: UTF-8");
  require_once( '../../../../../wp-load.php' );

  echo '#asw-filter-button, #asw-attributes-button { color:' . get_option('asw_filter_button_color') . '; background: ' . get_option('asw_filter_button_background') . '}';

  echo '#asw-loader { border: 16px solid ' . get_option('asw_loader_color_second') . '!important; border-top: 16px solid ' . get_option('asw_loader_color_first') . '!important; }';

  echo get_option('asw_css');
