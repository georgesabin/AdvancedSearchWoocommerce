<?php

	include_once(ASW_PLUGIN_DIR . 'includes/class.asw_admin.php');

	class ASWPublic {

		private static $SKU;

		private static $category;

		private static $ASWData;

		public static function init() {

			self::$ASWData = (object)$_POST;

			self::$SKU = get_option('asw_sku');

			self::$category = get_option('asw_category');

			// add_action('woocommerce_before_main_content', array('ASWPublic','asw_output'));

			add_action('woocommerce_archive_description', array('ASWPublic','asw_output'));

			add_action('enable_sku', array('ASWPublic','asw_enable_sku'));

			add_action('enable_category', array('ASWPublic','asw_enable_category'));

			// Load JS & CSS files
			add_action('wp_enqueue_scripts', array('ASWPublic', 'asw_load_css_js'), 10);

			// Used in ajax
			add_action('wp_ajax_ASWQ', array('ASWPublic', 'asw_advancedSearchWoocommerceQuery'));
			add_action('wp_ajax_nopriv_ASWQ', array('ASWPublic', 'asw_advancedSearchWoocommerceQuery'));

			// Added plugin wrap for build filter
			add_action('woocommerce_before_shop_loop', array('ASWPublic', 'asw_before_class'), 10);
			// add_action('woocommerce_after_main_content', array('ASWPublic', 'asw_after_class'), 10);

		}

		public static function asw_enable_sku() {

			global $query;

			if (is_array(self::$SKU) && array_key_exists('asw_sku', self::$SKU)) {

				if (self::$SKU['asw_sku'] == 'enable') { ?>

					<input type="search" name="s" />

				<?php }

			}

		}

		public static function asw_enable_category() {

			if (self::$category === 'enable') {
			?>

			<select name="product_cat">

				<option value="" disable><?php echo __('Select a category', 'sgmedia-asw'); ?></option>

				<?php //Display ascendant all categories even those which don't have products

		            $terms = get_terms( 'product_cat', 'order=ASC&hide_empty=0' );

		            foreach($terms as $term) {

		                echo '<option value="' . $term->slug . '">' . $term->name . '</option>';

		            }

		        ?>

			</select>

			<?php
		}

		}

		public static function asw_output($args) {

			echo '<input type="hidden" name="post_type" value="product" />';
			echo '<input type="hidden" name="asw_nonce" value="' . wp_create_nonce('generate-nonce') . '" />';
			do_action('enable_category');
			do_action('enable_sku');
			// echo '<button type="submit">' . __('Submit', 'sgmedia-asw'). '</button>';

		}

		public static function asw_advancedSearchWoocommerceQuery() {

			global $wp_query;

			// Build the tax query
			$tax_query = [];

			// Build the meta query
			$meta_query = [];

			// Build the orderby
			$order_by = [];

			// Check if product_cat is not empty string
			if (isset(self::$ASWData->product_cat) && self::$ASWData->product_cat !== '') {

				// Set the tax_query
				$tax_query = [
					[
						'taxonomy' => 'product_cat',
						'field' => 'slug',
						'terms' => sanitize_text_field(self::$ASWData->product_cat) //if would have been more -> array('term1', 'term2' etc)
					]
				];

			}

			/**
			* Check if was make a request for sort
			**/
			if (isset(self::$ASWData->orderby) && self::$ASWData->orderby !== '') {

				switch (self::$ASWData->orderby) {
					case 'menu_order':
						$meta_key = '';
						$order = 'asc';
						$orderby = 'menu_order title';
						break;
					case 'popularity':
						$meta_key = '';
						$order = 'desc';
						$orderby = 'total_sales';
						break;
					case 'price':
		        $meta_key = '_regular_price';
		        $order = 'asc';
		        $orderby = 'meta_value_num';
		        break;
		    case 'price-desc':
		        $meta_key = '_regular_price';
		        $order = 'desc';
		        $orderby = 'meta_value_num';
		        break;
			    case 'date':
		        $meta_key = '';
		        $order = 'desc';
		        $orderby = 'date';
		        break;
			    case 'rating':
		        $meta_key = '';
		        $order = 'desc';
		        $orderby = 'rating';
		        break;
					default:
						break;
				}

			}

			$meta_query = [
				'relation' => 'AND',
				[
					'key' => '_regular_price',
					'value' => '',
					'compare' => '<'
				],
				[
					'key' => '_regular_price',
					'value' => '',
					'compare' => '>'
				]
			];

			// Build a new query posts
			$asw_query = array(
				'post_type' => sanitize_text_field(self::$ASWData->post_type),
				'posts_per_page' => get_option('posts_per_page'),
				'paged' => sanitize_text_field(self::$ASWData->paged),
				'tax_query' => $tax_query,
				'orderby' => $orderby,
				'order' => $order,
				'meta_key' => $meta_key,
				// 'meta_query' => $meta_query
			);

			// Set the query posts
			query_posts($asw_query);

			if (have_posts()) {

				// I don't want the sorting anymore
				do_action('woocommerce_before_shop_loop');

				echo '<ul class="products">';
					while (have_posts()) {

						the_post();
						wc_get_template_part('content', 'product');

					}
				echo '</ul>';

				/*  woocommerce pagination  */
				do_action('woocommerce_after_shop_loop');

			} else if (!woocommerce_product_subcategories(array('before' => woocommerce_product_loop_start(false), 'after' => woocommerce_product_loop_end(false)))) {

				wc_get_template('loop/no-products-found.php');

			}

			wp_reset_query();

			wp_die();

		}

			public static function asw_load_css_js() {

				wp_register_script('asw-public', ASW_PLUGIN_URL . 'public/js/asw_public.js', array(), false, true);
				wp_localize_script('asw-public', 'myAjax', array(
					'ajaxurl' => admin_url('admin-ajax.php')
				));
				wp_enqueue_script('asw-public');

				wp_enqueue_script('select2', ASW_PLUGIN_URL . 'public/js/select2.min.js', array(), false, true);
				wp_enqueue_style('select2', ASW_PLUGIN_URL . 'public/css/select2.min.css');

			}

			public static function asw_before_class() {

				echo '<div class="asw_wrap">';

			}

			public static function asw_after_class() {

				echo '</div>';

			}


	}
