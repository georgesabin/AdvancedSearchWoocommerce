<?php

  class ASWDeactivator {

    public function __construct() {

    

    }

    public static function plugin_deactivation() {

      /* Delete transient, only display this notice once. */
      delete_option('active_plugin');
        var_dump('here3');

    }

  }
