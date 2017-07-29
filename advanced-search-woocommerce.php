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

	// Define the ASW path
	define('ASW_PLUGIN_DIR', plugin_dir_path( __FILE__ ));

	if (is_admin()) {
		require_once(ASW_PLUGIN_DIR . 'includes/class.asw_admin.php');
		ASWAdmin::init();

		//register_deactivation_hook( __FILE__, array( 'ASW', 'plugin_deactivation' ) );
	}

	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

	// check for plugin using plugin name
	if ( is_plugin_active( 'woocommerce/woocommerce.php' ) && !is_admin()) {
	    require_once(ASW_PLUGIN_DIR . 'includes/class.asw_public.php');
		ASWPublic::init();
		//ASWPublic::asw_change_rules();
		//if (isset($_GET) && !empty($_GET)) {
			ASWPublic::asw_change_rules();
		//}
	}
