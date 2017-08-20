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

      // Load core scripts
      // add_action('wp_enqueue_scripts', ['ASW', 'core_scripts']);
      // add_action('admin_enqueue_scripts', ['ASW', 'core_scripts']);

    }

    public function set_internationalization() {

      add_action('plugins_loaded', array('ASW_i18n', 'asw_load_plugin_textdomain'));

    }

    public function init_admin() {

      if (is_admin()) {
        ASWAdmin::init();
      }

    }

    public function init_public() {

      ASWPublic::init();

    }

    public static function core_scripts() {

      wp_enqueue_script('bootstrap', ASW_PLUGIN_URL . 'general/js/bootstrap.min.js');
      wp_enqueue_style('bootstrap', ASW_PLUGIN_URL . 'general/css/bootstrap.min.css', false, '', false);
      // wp_enqueue_style('bootstrap-grid', ASW_PLUGIN_URL . 'general/css/bootstrap-grid.min.css');
      // wp_enqueue_style('bootstrap-reboot', ASW_PLUGIN_URL . 'general/css/bootstrap-reboot.min.css');

    }

    public static function get_all_products_price() {

      /**
      * Create a new query and get the id from each product
      * Set min and max price and send to JS file for create a range slider
      **/
      $products = new WP_Query(array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'fields' => 'ids',
        'posts_per_page' => -1
      ));
      foreach ($products->posts as $productID) {
        if (wc_get_product($productID)->get_regular_price() !== '') {
          $productsPrice[] = (float)wc_get_product($productID)->get_regular_price();
        }
      }

      return $productsPrice;

    }

  }
