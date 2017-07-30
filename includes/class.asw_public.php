<?php

	include_once(ASW_PLUGIN_DIR . 'includes/class.asw_admin.php');

	class ASWPublic {

		private static $SKU;

		private static $category;

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

		public static function asw_change_rules() {

			//add_filter('query_vars', array('ASWPublic', 'asw_parametersQueryVars'));

			add_filter('pre_get_posts', array('ASWPublic', 'asw_advancedSearchWoocommerceQuery'));

			// add_action('init', array('ASWPublic', 'asw_rewriteRule'), 10, 0);

			// add_action('template_redirect', array('ASWPublic', 'asw_redirectAfterSearch'));

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

		/**
			* add new query vars into global array $qvars
			* @param $qvars
			* @return array
		*/

			public static function asw_parametersQueryVars($qvars) {
				var_dump('1');
				$qvars[] = 'product_cat';
				return $qvars;
			}

		/**
			* after search in WP_Query will be all products with that category
			* @param $query
			* @return object
		*/

			public static function asw_advancedSearchWoocommerceQuery() {
				global $wp_query;
				// var_dump($wp_query); exit;
				// var_dump('2');
				$myQuery = [];
				// if (!is_search() && !is_archive())
				// 	return;
				// $category = urldecode(get_query_var('product_cat')) ? urldecode(get_query_var('product_cat')) : '';
				if ($_POST['product_cat'] !== '') {
					$myQuery[] = array(
						'taxonomy' => 'product_cat',
						'field' => 'slug',
						'terms' => $_POST['product_cat'] //if would have been more -> array('term1', 'term2' etc)
					);
				}
				// var_dump(get_query_var('product_cat'));
				$wp_query->set('tax_query', $myQuery);
				var_dump($wp_query->query_vars);
				// die();
				return $wp_query;
			}

		/**
			* Rewrite rule, that after search WP redirects at a URI of that form: domain/category/s (e.g: domain/sport/shirt)
		*/

			public static function asw_rewriteRule() {
var_dump('3');
				$regex1 = 'shop/([^/]+)/?$';
				$regex2 = 'categorie/([^/]+)/([^/]+)/?$';
				add_rewrite_rule($regex1,'index.php?post_type=product&product_cat=$matches[1]','top');
				add_rewrite_rule($regex2,'index.php?post_type=product&product_cat=$matches[1]&s=$matches[2]','top');

			}


		/**
			* I created a redirect
		*/

			public static function asw_redirectAfterSearch() {
				var_dump('4');
				/*global $wp_rewrite;
				print_r($wp_rewrite);*/
				//global $wp_query; var_dump($wp_query);
				//if (is_search() || is_archive() || is_shop()) {
					$s = $_GET['s'] ? $_GET['s'] : false;
					$category = $_GET['product_cat'] ? $_GET['product_cat'] : false;
					$url = '/';
					var_dump($s, 'test', $_GET);
					if ($category && $s == false) {
						var_dump('dadada');
						$url .= 'shop/' . $category;
					} else if ($s && $category) {
						$url .= 'categorie/' . $category . '/' . $s;
					}
					if ($url != '/') {
						wp_redirect(get_bloginfo('url') . $url);
				    	exit();
					}
				//}
			}

			public static function asw_load_js() {

				wp_register_script('asw-public', ASW_PLUGIN_URL . 'public/js/asw_public.js', array(), false, true);
				wp_localize_script('asw-public', 'myAjax', array('ajaxurl' => admin_url('admin-ajax.php')));

				wp_enqueue_script('asw-public');

			}

	}
