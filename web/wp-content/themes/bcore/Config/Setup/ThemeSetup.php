<?php 
/**
 * ThemeSetup.php
 */

namespace Config\Setup;
use Config\BcoreConfig;

class ThemeSetup extends BcoreConfig {

	public function __construct() {
		add_action('after_setup_theme', array($this, 'bco_theme_setup'));
		add_filter('wp_insert_post_data', array($this, 'default_menu_order'));
		add_action('wp_insert_post_data', array($this, 'comments_off_by_default'));
		add_action('add_meta_boxes', array($this, 'remove_custom_fields_metabox'));
	}

	
	public function bco_theme_setup() {

		if ( ! isset( $content_width ) ) { $content_width = 1200; }

		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		add_image_size('placeholder', 30, 30);
		add_image_size('featured', 600, 380, true);
		update_option('thumbnail_size_w', 300);
		update_option('thumbnail_size_h', 300);
		update_option('medium_size_w', 600);
		update_option('medium_size_h', 600);
		update_option('large_size_w', 1500);
		update_option('large_size_h', 1500);

		add_filter(
			'wpseo_metabox_prio',
			function() {
				return 'low';
			}
		);

		add_filter(
			'gform_confirmation_anchor',
			create_function(
				'',
				'return false;'
			)
		);


		if ( !current_user_can('administrator') && !is_admin() ) {
			show_admin_bar(false);
		}

		/**
		 * Add ACF Custom Options Page
		 */
		// if ( function_exists('acf_add_options_page') ) {
		// 	acf_add_options_page(
		// 		array(
		// 			'page_title' => get_bloginfo('title') . ' Website Settings',
		// 			'menu_title' => get_bloginfo('title') . ' Website Settings',
		// 			'menu_slug' => 'bco-general-settings',
		// 			'parent_slug' => 'themes.php',
		// 			'capability' => 'edit_posts',
		// 			'redirect' => false
		// 		)
		// 	);
		// }
	}

	public function default_menu_order($data) {
		if ( $data['post_status'] == 'auto-draft' ) {
			$data['menu_order'] = 1000;
		}
		return $data;
	}

	public function comments_off_by_default( $data ) {
		if ( $data['post_type'] == 'page' && $data['post_status'] == 'auto-draft' ) {
			$data['comment_status'] = 0;
		}
		if ( $data['post_type'] == 'post' && $data['post_status'] == 'auto-draft' ) {
			$data['comment_status'] = 0;
		}
		return $data;
	}

	public function remove_custom_fields_metabox() {
		global $post_type;
		if ( is_admin() && post_type_supports( $post_type, 'custom-fields' ) ) {
			remove_meta_box( 'postcustom', 'page', 'normal' );
			remove_meta_box( 'postcustom', 'post', 'normal' );
		}
	}
}