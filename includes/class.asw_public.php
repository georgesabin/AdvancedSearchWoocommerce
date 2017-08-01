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

			add_action('woocommerce_before_main_content', array('ASWPublic','asw_output'));

			add_action('enable_sku', array('ASWPublic','asw_enable_sku'));

			add_action('enable_category', array('ASWPublic','asw_enable_category'));

			// Load JS files
			add_action('wp_enqueue_scripts', array('ASWPublic', 'asw_load_js'), 10);

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

		public static function asw_output($args) {

			//var_dump(self::$all_settings);
			//echo 'aici ' . get_option('asw_sku');
			//global $wp_query; var_dump($wp_query);
			?>

			<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<input type="hidden" name="post_type" value="product" />
				<?php do_action('enable_category'); ?>
				<?php do_action('enable_sku'); ?>
				<button type="submit"><?php echo __('Submit', 'sgmedia-asw'); ?></button>
			</form>

			<?php

		}

		public static function asw_advancedSearchWoocommerceQuery() {

			global $wp_query;

			// Build the tax query
			$tax_query = [];

			// Build the orderby
			$order_by = [];

			// Check if product_cat is not empty string
			if (isset(self::$ASWData->product_cat) && self::$ASWData->product_cat !== '') {

				// Set the tax_query
				$tax_query = [
					[
						'taxonomy' => 'product_cat',
						'field' => 'slug',
						'terms' => self::$ASWData->product_cat //if would have been more -> array('term1', 'term2' etc)
					]
				];

			}

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
		        $meta_key = '_price';
		        $order = 'asc';
		        $orderby = 'meta_value_num';
		        break;
		    case 'price-desc':
		        $meta_key = '_price';
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

			// Build a new query posts
			$asw_query = array(
				'post_type' => $_POST['post_type'],
				'posts_per_page' => get_option( 'posts_per_page' ),
				'paged' => ($_POST['paged'] !== '' ? (int)$_POST['paged'] : ''),
				'tax_query' => $tax_query,
				'orderby' => $orderby,
				'order' => $order,
				'meta_key' => $meta_key
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

			public static function asw_load_js() {

				wp_register_script('asw-public', ASW_PLUGIN_URL . 'public/js/asw_public.js', array(), false, true);
				wp_localize_script('asw-public', 'myAjax', array(
					'ajaxurl' => admin_url('admin-ajax.php')
				));

				wp_enqueue_script('asw-public');

			}

			public static function asw_before_class() {

				echo '<div class="asw_wrap">';

			}

			public static function asw_after_class() {

				echo '</div>';

			}


	}
