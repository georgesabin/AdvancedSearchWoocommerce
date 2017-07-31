<?php

	include_once(ASW_PLUGIN_DIR . 'includes/class.asw_admin.php');

	class ASWPublic {

		private static $SKU;

		private static $category;

		public $hmltProd = '';

		public static function init() {

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

		}

		public static function asw_enable_sku() {

			global $query;
			// var_dump($query);

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
				$asw_query = array(
					'post_type' => $_GET['post_type'],
					'tax_query' => array(
						array(
							'taxonomy' => 'product_cat',
							'field' => 'slug',
							'terms' => $_GET['product_cat'] //if would have been more -> array('term1', 'term2' etc)
						)
					)
				);
				query_posts($asw_query);

				do_action('woocommerce_archive_description'); ?>

			 <?php if (have_posts()) : ?>

				<?php woocommerce_result_count(); ?>

					 <?php
					 // I don't want the sorting anymore
					 //do_action('woocommerce_before_shop_loop');
					 ?>

					 <ul class = "products-list">
							 <?php while (have_posts()) : the_post(); ?>

									 <?php wc_get_template_part('content', 'product'); ?>

							 <?php endwhile; // end of the loop.   ?>
					 </ul>

					 <?php
					 /*  woocommerce pagination  */
					 do_action('woocommerce_after_shop_loop');
					 ?>

			 <?php elseif (!woocommerce_product_subcategories(array('before' => woocommerce_product_loop_start(false), 'after' => woocommerce_product_loop_end(false)))) : ?>

					 <?php woocommerce_get_template('loop/no-products-found.php'); ?>

			 <?php endif;

			wp_die();

		}

			public static function asw_load_js() {

				wp_register_script('asw-public', ASW_PLUGIN_URL . 'public/js/asw_public.js', array(), false, true);
				wp_localize_script('asw-public', 'myAjax', array(
					'ajaxurl' => admin_url('admin-ajax.php')
				));

				wp_enqueue_script('asw-public');

			}

	}
