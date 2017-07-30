<?php
	/*
	Plugin Name: Advanced Search Woocommerce
	Plugin URI:
	Description: Basic WordPress Plugin Header Comment
	Version:     0.1
	Author:      SG Media Freelance WP Developer
	Author URI:  https://www.sgmedia.ro/
	License:     GPL2
	License URI: https://www.gnu.org/licenses/gpl-2.0.html
	Text Domain: sgmedia-asw
	Domain Path: /languages

	Advanced Search Woocommerce is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 2 of the License, or
	any later version.

	Advanced Search Woocommerce is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with SG Media Advanced Search Woocommerce. If not, see {License URI}.
	*/

	//Exit if accesed directly
	if (!defined('ABSPATH')) {
		exit;
	}

	include_once(ABSPATH . 'wp-admin/includes/plugin.php');

	// Check if Woocommerce is activated on WP site
	if (is_plugin_active('woocommerce/woocommerce.php')) {

		// Define the ASW path
		define('ASW_PLUGIN_DIR', plugin_dir_path( __FILE__ ));

		require_once(ASW_PLUGIN_DIR . 'includes/class.asw_activator.php');
		require_once(ASW_PLUGIN_DIR . 'includes/class.asw_deactivator.php');
		require_once(ASW_PLUGIN_DIR . 'includes/class.asw_i18n.php');
		require_once(ASW_PLUGIN_DIR . 'includes/class.asw.php');
		require_once(ASW_PLUGIN_DIR . 'includes/class.asw_public.php');
		require_once(ASW_PLUGIN_DIR . 'includes/class.asw_admin.php');

		$activator = new ASWActivator();
		$activator->init();
		register_activation_hook(__FILE__, array($activator, 'plugin_activation'));

		$deactivator = new ASWDeactivator();
	  register_deactivation_hook(__FILE__, array($deactivator, 'plugin_deactivation'));
		
		$i18n = new ASW_i18n();

		$asw = new ASW();
		$asw->set_internationalization();
		$asw->init_admin();
		$asw->init_public();

		// require_once(ASW_PLUGIN_DIR . 'includes/class.asw_public.php');
		// ASWPublic::init();
		// //ASWPublic::asw_change_rules();
		// //if (isset($_GET) && !empty($_GET)) {
		// 	ASWPublic::asw_change_rules();
		// //}
		// }
	}
