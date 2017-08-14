<?php

	class ASWAdmin {

		private $all_settings;

		/**
		* Function for init functionality
		**/
		public static function init() {

			add_action('admin_menu', array('ASWAdmin', 'asw_menu'));

			add_action('admin_init',array('ASWAdmin', 'asw_register_setting'));

			add_action('admin_enqueue_scripts', array('ASWAdmin', 'asw_admin_scripts'));

		}

		/**
		* Create the group of settings and add in group new settings
		**/
		public static function asw_register_setting() {

			// General tab
			add_settings_section('asw_section', '', array('ASWAdmin', 'asw_general_title'), 'asw_general');

			// Settings tab
	   	register_setting('asw_settings', 'asw_sku');
			register_setting('asw_settings', 'asw_category');
			register_setting('asw_settings', 'asw_stock_status');
			register_setting('asw_settings', 'asw_slide_regular_price');
			register_setting('asw_settings', 'asw_min_regular_price');
	    register_setting('asw_settings', 'asw_max_regular_price');

	    add_settings_section('asw_section', '', array('ASWAdmin', 'asw_settings_title'), 'asw_settings');

	    add_settings_field('asw_sku_field', __('SKU','sgmedia-asw'), array('ASWAdmin','asw_field_sku'), 'asw_settings', 'asw_section');

			add_settings_field('asw_category_field', __( 'Category', 'sgmedia-asw' ), array('ASWAdmin','asw_field_category'), 'asw_settings', 'asw_section');

			add_settings_field('asw_stock_status_field', __( 'Stock status', 'sgmedia-asw' ), array('ASWAdmin','asw_field_stock_status'), 'asw_settings', 'asw_section');

			add_settings_field('asw_slide_regular_price_field', __( 'Slide regular price', 'sgmedia-asw' ), array('ASWAdmin','asw_field_slide_regular_price'), 'asw_settings', 'asw_section');

			add_settings_field('asw_min_regular_price', __( 'Min regular price', 'sgmedia-asw' ), array('ASWAdmin','asw_field_min_regular_price'), 'asw_settings', 'asw_section');

	    add_settings_field('asw_max_regular_price', __( 'Max regular price', 'sgmedia-asw' ), array('ASWAdmin','asw_field_max_regular_price'), 'asw_settings', 'asw_section');

			// Custom style tab
			add_settings_section('asw_section', '', array('ASWAdmin', 'asw_custom_style_title'), 'asw_custom_style');

			register_setting('asw_custom_style', 'asw_css');

			add_settings_field('asw_css_field', __('CSS', 'sgmedia-asw'), array('ASWAdmin', 'asw_field_css'), 'asw_custom_style', 'asw_section');

		}

		/**
		* General section
		* @method asw_general_title - public static
		**/

		public static function asw_general_title($args) {

				echo '<h2>' . __('General', 'sgmedia-asw') . '</h2>';

		}

		/**
		* Settings section
		* @method asw_settings_title - public static
		* @method asw_field_sku - public static
		* @method asw_field_category - public static
		* @method asw_field_stock_status - public static
		* @method asw_field_slide_regular_price - public static
		* @method asw_field_min_regular_price - public static
		* @method asw_field_max_regular_price - public static
		**/

		public static function asw_settings_title($args) {

				echo '<h2>' . __('Settings', 'sgmedia-asw') . '</h2>';

		}

		public static function asw_field_sku($args) {

			$sku = get_option('asw_sku');

			echo '<input type="checkbox" name="asw_sku" value="disable" ' . (!empty($sku) ? checked(esc_attr($sku), 'disable', false) : '') . '>' . __('Disable SKU search', 'sgmedia-asw');

		}

		public static function asw_field_category($args) {

			$category = get_option('asw_category');

			echo '<input type="checkbox" name="asw_category" value="disable" ' . (!empty($category) ? checked(esc_attr($category), 'disable', false) : '') . '>' . __('Disable Category search', 'sgmedia-asw');

		}

		public static function asw_field_stock_status($args) {

			$status = get_option('asw_stock_status');

			echo '<input type="checkbox" name="asw_stock_status" value="disable" ' . (!empty($status) ? checked(esc_attr($status), 'disable', false) : '') . '>' . __('Disable Status search', 'sgmedia-asw');

		}

		public static function asw_field_slide_regular_price($args) {

			$slide = get_option('asw_slide_regular_price');

			echo '<input type="checkbox" name="asw_slide_regular_price" value="disable" ' . (!empty($slide) ? checked(esc_attr($slide), 'disable', false) : '') . '>' . __('Disable Slide regular price', 'sgmedia-asw');

		}

		public static function asw_field_min_regular_price($args) {

			$slide = get_option('asw_slide_regular_price');

			$min_regular_price = get_option('asw_min_regular_price');

			$minPrice = min(ASW::get_all_products_price());
			$maxPrice = max(ASW::get_all_products_price());

			?>
			<input id="asw-min-regular-price" type="number" name="asw_min_regular_price" min="<?php echo $minPrice; ?>" max="<?php echo $maxPrice; ?>" value="<?php echo esc_attr($min_regular_price); ?>" <?php echo isset($slide) && $slide === 'disable' ? 'disabled' : ''; ?>>

			<?php

		}

		public static function asw_field_max_regular_price($args) {

			$slide = get_option('asw_slide_regular_price');

			$max_regular_price = get_option('asw_max_regular_price');

			$minPrice = min(ASW::get_all_products_price());
			$maxPrice = max(ASW::get_all_products_price());

			?>
			<input id="asw-max-regular-price" type="number" name="asw_max_regular_price" min="<?php echo $minPrice; ?>" max="<?php echo $maxPrice; ?>" value="<?php echo esc_attr($max_regular_price); ?>" <?php echo isset($slide) && $slide === 'disable' ? 'disabled' : ''; ?> data-toggle="tooltip" data-placement="right" title="Tooltip on top">

			<?php

		}

		/**
		* Custom style section
		* @method asw_custom_style_title - public static
		* @method asw_field_css - public static
		**/
		public static function asw_custom_style_title($args) {

				echo '<h2>' . __('Custom style', 'sgmedia-asw') . '</h2>';

		}

		public static function asw_field_css($args) {

			$custom_css_default = __('/*
				Welcome to the Custom CSS editor!

				Please add all your custom CSS here and avoid modifying the core theme files, since that\'ll make upgrading the theme problematic. Your custom CSS will be loaded after the theme\'s stylesheets, which means that your rules will take precedence. Just add your CSS here for what you want to change, you don\'t need to copy all the theme\'s style.css content.
				*/'
			);

			$css = get_option('asw_css', $custom_css_default);

			echo '<div id="custom_css_container">';
				echo '<div name="asw_css" id="asw_css" style="border: 1px solid #DFDFDF; -moz-border-radius: 3px; -webkit-border-radius: 3px; border-radius: 3px; width: 100%; height: 400px; position: relative;"></div>';
			echo '</div>';
			echo '<textarea id="asw_css_textarea" name="asw_css" style="display: none;">' . $css . '</textarea>';

		}

		public function setAllSettings() {
//modifica
			$this->all_settings = [];
			$this->all_settings['sku'] = get_option('asw_sku');
			$this->all_settings['options'] = get_option('asw_options');
			var_dump($this->all_settings);

		}

		public function getAllSettings() {

			return $this->all_settings;

		}

		// Adaug in pagina de plugin setarile si butonul de submit
		public static function asw_admin() {

			// check user capabilities
	    if (!current_user_can('manage_options')) {
	        return;
	    }

			// check if the user have submitted the settings
			// wordpress will add the "settings-updated" $_GET parameter to the url
			if (isset($_GET['settings-updated'])) {
				// add settings saved message with the class of "updated"
				add_settings_error('asw_messages', 'asw_message', __( 'Settings Saved', 'sgmedia-asw' ), 'updated');
			}

	    ?>
			<div class="wrap">
	      <div id="icon-themes" class="icon32"></div>
	      <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
	      <?php settings_errors('asw_messages'); ?>
	      <?php
					$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'general';
					$tabs = [
						(object)[
							'name' => 'general',
							'title' => 'General'
						],
						(object)[
							'name' => 'settings',
							'title' => 'Settings'
						],
						(object)[
							'name' => 'custom_style',
							'title' => 'Custom Style'
						]
					];
				?>

	      <h2 class="nav-tab-wrapper">
					<?php
						foreach ($tabs as $key => $tab) {
							echo '<a href="?page=asw&tab=' . $tab->name . '" class="nav-tab ' . ($active_tab === $tab->name ? 'nav-tab-active' : '') . '">' . __($tab->title, 'sgmedia-asw') . '</a>';
						}
					?>
	      </h2>
	      <form id="asw-form" method="post" action="options.php">
	        <?php
						switch ($active_tab) {
							case 'general':
								settings_fields('asw_general');
								do_settings_sections('asw_general');
								break;
							case 'settings':
								settings_fields('asw_settings');
								do_settings_sections('asw_settings');
								break;
							case 'custom_style':
								settings_fields('asw_custom_style');
								do_settings_sections('asw_custom_style');
								break;
							default:
								# code...
								break;
						}
	        ?>
	        <?php submit_button(); ?>
	      </form>
    	</div>
		  <?php

		}

		// Creez item menu in meniu din WP
		public static function asw_menu() {
			/**
			* add_menu_page( string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '', string $icon_url = '', int $position = null )
			**/
	        add_submenu_page('woocommerce', __('Advanced Search Woocommerce by SG Media','sgmedia-asw'), __('ASW by SG Media','sgmedia-asw'), 'manage_options', 'asw', array('ASWAdmin', 'asw_admin'));
		}

		/**
		* With bootstrap-include.js file will be include js and css files
		**/
		public static function asw_admin_scripts($hook_suffix) {

			// Load JS & CSS files just in the plugin page
			if ($hook_suffix === 'woocommerce_page_asw') {
				// wp_enqueue_script('tether', ASW_PLUGIN_URL . 'general/js/tether.min.js');
				// wp_enqueue_script('bootstrap-include-css', ASW_PLUGIN_URL . 'admin/js/bootstrap-include.js');
				// wp_enqueue_script('bootstrap', ASW_PLUGIN_URL . 'general/js/bootstrap.min.js');

				wp_enqueue_script( 'ace_code_highlighter_js', ASW_PLUGIN_URL . 'ace/ace.js', '', '1.0.0', true );
        wp_enqueue_script( 'ace_mode_js', ASW_PLUGIN_URL . 'ace/mode-css.js', array( 'ace_code_highlighter_js' ), '1.0.0', true );
        wp_enqueue_script( 'custom_css_js', ASW_PLUGIN_URL . 'admin/js/custom_css.js', array( 'jquery', 'ace_code_highlighter_js' ), '1.0.0', true );

				wp_enqueue_script('asw-admin', ASW_PLUGIN_URL . 'admin/js/asw_admin.js');
				wp_enqueue_style('admin-style', ASW_PLUGIN_URL . 'admin/css/style.css');
			}

		}

		public static function asw_activation_notice() {

			/* Check transient, if available display notice */
		    if( get_option('active_plugin' ) == 'message'){
		        ?>
		        <div class="updated notice is-dismissible">
		            <p>Thank you for using this plugin! <strong>You are awesome</strong>.</p>
		        </div>
		        <?php
		        /* Delete transient, only display this notice once. */
		        delete_option('active_plugin');
		    }

		}

		public static function plugin_activation() {

			/* Create option - used in asw_activation_notice */
    		add_option('active_plugin', 'message');
			flush_rewrite_rules();

		}

		public static function plugin_deactivation( ) {

			flush_rewrite_rules();

		}

	}
