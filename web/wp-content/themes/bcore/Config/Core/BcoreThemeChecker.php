<?php 
/**
 * BcoreThemeChecker.php
 */
namespace Config\Core;
use Config\BcoreConfig;

class BcoreThemeChecker extends BcoreConfig {

	public function __construct() {
		add_action('admin_bar_menu', array($this, 'admin_bar_dev_env'));

		if ( $this->show_developer_messages() ) {
			add_action('admin_notices', array($this, 'parent_framework_check'));
			add_action('admin_notices', array($this, 'blog_description_check'));
			add_action('admin_notices', array($this, 'screenshot_check'));
			add_action('admin_notices', array($this, 'discourage_search_engines_check'));
			add_action('admin_notices', array($this, 'discourage_search_engines_check_beta'));
			add_action('admin_notices', array($this, 'check_for_debug_mode_on_live'));
			add_action('admin_notices', array($this, 'check_for_favicon'));
			add_action('admin_notices', array($this, 'check_admin_email'));
			add_action('admin_notices', array($this, 'check_for_index_bug'));
		}
	}

	private function get_development_environment() {
		return get_option('development_environment');
	}

	private function show_developer_messages() {
		if ( (int) get_option('dev_warnings') !== 1 ) {
			return true;
		} else {
			return false;
		}
	}

	private function get_developer_options_link() {
		return get_admin_url() . 'customize.php?autofocus[section]=developers_section';
	}

	
	public function admin_bar_dev_env($admin_bar) {
		$env = $this->get_development_environment();
		if ( $env === 'local' ) {
			$title = 'Status: Local Dev';
		} elseif ( $env === 'beta' ) {
			$title = 'Status: In Beta';
		} else {
			$title = 'Status: Live';
		}
		$admin_bar->add_menu( 
			array(
				'id' => 'bcore-dev-env-' . $env,
				'parent' => 'top-secondary',
				'title' => $title,
				'href' => $this->get_developer_options_link()
			) 
		);
	}

	public function parent_framework_check() {
		$current_theme = get_option('stylesheet');
		if ($current_theme === BCORE_PARENT_DIRECTORY_NAME) {
			?>
				<div class="notice notice-error is-dismissible">
					<p><?php _e('😥 Current theme is set to Brandco. Framework, please use a child theme. <a href="' . $this->get_developer_options_link() . '">Hide developer alerts.</a>', 'bco'); ?></p>
				</div>
			<?php
		}
	}

	public function blog_description_check() {
		$description = get_option('blogdescription');
		if (strpos($description, 'Just another WordPress') !== false) {
			?>
				<div class="notice notice-error is-dismissible">
					<p><?php _e('😑 Make a better blog description, right now it says "' . $description . '". <a href="' . $this->get_developer_options_link() . '">Hide developer alerts.</a>', 'bco'); ?></p>
				</div>
			<?php
		}
	}

	public function screenshot_check() {
		$screenshot_png = BCORE_CHILD_BASE_DIRECTORY . '/screenshot.png';
		$screenshot_jpg = BCORE_CHILD_BASE_DIRECTORY . '/screenshot.jpg';
		if ( file_exists($screenshot_png) || file_exists($screenshot_jpg) ) {
		} else {
			?>
				<div class="notice notice-error is-dismissible">
					<p><?php _e('📷 Current theme does not have a screenshot file. <a href="' . $this->get_developer_options_link() . '">Hide developer alerts.</a>', 'bco'); ?></p>
				</div>
			<?php
		}
	}

	public function discourage_search_engines_check() {
		$public = get_option('blog_public');
		if ( $this->get_development_environment() === 'live' && (int) $public === 0 ) {
			?>
				<div class="notice notice-error is-dismissible">
					<p><?php _e('👀 "Discourage search engines" is currently on (Settings > Reading). <a href="' . $this->get_developer_options_link() . '">Hide developer alerts.</a>', 'bco'); ?></p>
				</div>
			<?php	
		}
	}

	public function discourage_search_engines_check_beta() {
		$public = get_option('blog_public');
		if ( $this->get_development_environment() === 'beta' && (int) $public === 1 ) {
			?>
				<div class="notice notice-error is-dismissible">
					<p><?php _e('👀 "Discourage search engines" should be on during beta (Settings > Reading). <a href="' . $this->get_developer_options_link() . '">Hide developer alerts.</a>', 'bco'); ?></p>
				</div>
			<?php	
		}
	}

	public function check_for_debug_mode_on_live() {
		if ( $this->get_development_environment() === 'live' && defined('WP_DEBUG') && WP_DEBUG === true ) {
			?>
				<div class="notice notice-error is-dismissible">
					<p><?php _e('🐛 Debug mode is on. <a href="' . $this->get_developer_options_link() . '">Hide developer alerts.</a>', 'bco'); ?></p>
				</div>
			<?php	
		}
	}

	public function check_for_favicon() {
		if ( get_option('site_icon') < 1 ) {
			?>
				<div class="notice notice-error is-dismissible">
					<p><?php _e('⭐ No favicon set. <a href="' . $this->get_developer_options_link() . '">Hide developer alerts.</a>', 'bco'); ?></p>
				</div>
			<?php			
		}
	}

	public function check_admin_email() {
		if ($this->get_development_environment() === 'live' && strpos(get_option('admin_email'), 'brandco') !== false) {
			?>
				<div class="notice notice-error is-dismissible">
					<p><?php _e('✉️ Admin email is set to a brandco address. Make sure forms are submitting to the right place. <a href="' . $this->get_developer_options_link() . '">Hide developer alerts.</a>', 'bco'); ?></p>
				</div>
			<?php						
		}
	}

	public function check_for_index_bug() {
		if (strpos(get_option('permalink_structure'), 'index.php') !== false) {
			?>
				<div class="notice notice-error is-dismissible">
					<p><?php _e('💡 Permalink structure contains "index.php". To fix edit "/wp-includes/vars.php" and change "$is_apache = true". <a href="' . $this->get_developer_options_link() . '">Hide developer alerts.</a>', 'bco'); ?></p>
				</div>
			<?php						
		}
	}
}