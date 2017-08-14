<?php
  header("Content-type: text/css; charset: UTF-8");
  require_once( '../../../../../wp-load.php' );
  
  echo get_option('asw_css');
