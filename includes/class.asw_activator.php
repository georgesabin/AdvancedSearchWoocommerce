<?php

  // set hook register_activation_hook(__FILE__, array('ASWActivator', 'plugin_activation'));

  class ASWActivator {

    public function __construct() {


    }

    public function init() {

      // Add the notice
      add_action('admin_notices', array('ASWActivator', 'asw_activation_notice'));

    }

    public static function asw_activation_notice() {

			/* Check transient, if available display notice */
		    if( get_option('active_plugin' ) == 'message') {
		        ?>
		        <div class="updated notice is-dismissible">
		            <p>Thank you for using this plugin! <strong>You are awesome</strong>.</p>
		        </div>
		        <?php
		    }

		}

		public static function plugin_activation() {

			/* Create option - used in asw_activation_notice */
    	add_option('active_plugin', 'message');
			flush_rewrite_rules();

		}

  }
