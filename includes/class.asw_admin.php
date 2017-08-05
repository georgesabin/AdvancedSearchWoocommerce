<?php

	class ASWAdmin {

		private $all_settings;

		// Initializez meniul si inregistrez setarile din pagina de plugin
		public static function init() {

			add_action('admin_menu', array('ASWAdmin', 'asw_menu'));

			add_action('admin_init',array('ASWAdmin', 'asw_register_setting'));

			add_action('admin_enqueue_scripts', array('ASWAdmin', 'asw_admin_scripts'));

		}

		// Creez grupul de setari si adaug in grup setari noi
		public static function asw_register_setting() {

		    register_setting('asw', 'asw_category');
		    register_setting('asw', 'asw_sku');

		    add_settings_section('asw_section', __( 'Section.', 'sgmedia-asw' ), array('ASWAdmin', 'asw_section_function'), 'asw');

		    add_settings_field('asw_field_sku', __('SKU','sgmedia-asw'), array('ASWAdmin','asw_field_sku'), 'asw', 'asw_section', array('label_for' => 'asw_sku', 'class' => 'asw_sku', 'asw_custom_data' => 'custom-sku'));

		    add_settings_field('asw_category_field', __( 'Category', 'sgmedia-asw' ), array('ASWAdmin','asw_field_category'), 'asw', 'asw_section');

		}

		// Functie callback setare
		public static function asw_section_function($args) {

			?>
				<p id="<?php echo esc_attr($args['id']); ?>"><?php esc_html_e('Follow the white rabbit.', 'sgmedia-asw'); ?></p>
			<?php

		}

		public static function asw_field_sku($args) {

			$sku = get_option('asw_sku');

			?>

			<input type="checkbox" name="asw_sku[<?php echo esc_attr($args['label_for']); ?>]" value="enable" <?php !empty($sku[$args['label_for']]) ? checked(esc_attr($sku[$args['label_for']]), 'enable', true) : ''; ?>> <?php echo __('Enable SKU search', 'sgmedia-asw'); ?>

			<?php

		}

		public static function asw_field_category($args) {

			?>

			<div class="bootstrap-wrapper">
     <div class="container">
          <div class="row">
               <div class="col-md-4">
                    <h3>This is a column!</h3>
               </div>
               <div class="col-md-4">
                    <h3>This is a column!</h3>
               </div>
               <div class="col-md-4">
                    <h3>This is a column!</h3>
               </div>
          </div>
     </div>
</div>

<?php

			$category = get_option('asw_category');

			?>

			<input type="checkbox" name="asw_category" value="enable" <?php !empty($category) ? checked(esc_attr($category), 'enable', true) : ''; ?>> <?php echo __('Enable Category search', 'sgmedia-asw'); ?>

			<?php

		}

		/*public static function asw_field_pill_cb($args) {

			// get the value of the setting we've registered with register_setting()

			$options = get_option( 'asw_category_select' );

			// output the field

			?>

			<select id="<?php echo esc_attr( $args['label_for'] ); ?>"
			data-custom="<?php echo esc_attr( $args['wporg_custom_data'] ); ?>"
			name="asw_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
			>

				<option value="red" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'red', false ) ) : ( '' ); ?>>

					<?php esc_html_e( 'red pill', 'wporg' ); ?>

				</option>

				<option value="blue" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'blue', false ) ) : ( '' ); ?>>

					<?php esc_html_e( 'blue pill', 'wporg' ); ?>

				</option>

			</select>

			<p class="description">

				<?php esc_html_e( 'You take the blue pill and the story ends. You wake in your bed and you believe whatever you want to believe.', 'wporg' ); ?>

			</p>

			<p class="description">

				<?php esc_html_e( 'You take the red pill and you stay in Wonderland and I show you how deep the rabbit-hole goes.', 'wporg' ); ?>

			</p>

			<?php

		}*/

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

		    // add error/update messages

			// check if the user have submitted the settings
			// wordpress will add the "settings-updated" $_GET parameter to the url
			if (isset($_GET['settings-updated'])) {
				// add settings saved message with the class of "updated"
				add_settings_error('asw_messages', 'asw_message', __( 'Settings Saved', 'sgmedia-asw' ), 'updated');
			}

			// show error/update messages
			settings_errors( 'asw_messages' );

		    ?>
		    <div class="wrap">
		        <h1><?php esc_html(get_admin_page_title()); ?></h1>
		        <form action="options.php" method="post">
		            <?php
		            // output security fields for the registered setting "sgmedia-asw_options"
		            settings_fields('asw');
		            // output setting sections and their fields
		            // (sections are registered for "sgmedia-asw", each field is registered to a specific section)
		            do_settings_sections('asw');
		            // output save settings button
		            submit_button('Save Settings');
		            ?>
		        </form>
		    </div>
		    <?php

		}

		// Creez item menu in meniu din WP
		public static function asw_menu() {
			/**
			* add_menu_page( string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '', string $icon_url = '', int $position = null )
			**/
	        add_submenu_page('woocommerce', __('SG Media Advanced Search Woocommerce','sgmedia-asw'), __('ASW by SG Media','sgmedia-asw'), 'manage_options', 'asw', array('ASWAdmin', 'asw_admin'));
		}

		/**
		* With bootstrap-include.js file will be include js and css files
		**/
		public static function asw_admin_scripts() {

			wp_enqueue_script('bootstrap-include-css', ASW_PLUGIN_URL . 'admin/js/bootstrap-include.js');

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
