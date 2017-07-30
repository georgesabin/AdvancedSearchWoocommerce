<?php

  /**
   * The file that defines the core plugin class
   *
   * A class definition that includes attributes and functions used across both the
   * public-facing side of the site and the admin area.
   *
   * @link       http://sgmedia.ro
   * @since      1.0.0
   *
   * @package    Plugin_Name
   * @subpackage Plugin_Name/includes
   */

  /**
   * The core plugin class.
   *
   * This is used to define internationalization, admin-specific hooks, and
   * public-facing site hooks.
   *
   * Also maintains the unique identifier of this plugin as well as the current
   * version of the plugin.
   *
   * @since      1.0.0
   * @package    Plugin_Name
   * @subpackage Plugin_Name/includes
   * @author     Your Name <email@example.com>
   */

  class ASW {

    protected $plugin_name;
    protected $version;

    public function __construct() {

      $plugin_name = 'Advanced Search Woocommerce';
      $version = '0.1';

    }

    public function set_internationalization() {

      add_action('plugins_loaded', array('ASW_i18n', 'asw_load_plugin_textdomain'));

    }

    public function init_admin() {

      if (is_admin()) {
        // require_once(ASW_PLUGIN_DIR . 'includes/class.asw_admin.php');
        ASWAdmin::init();
      }

    }

    public function init_public() {



    }

  }
