<?php
/**
 * BcoreLoginScreen.php
 */
namespace Config\Core;
use Config\BcoreConfig;

class BcoreLoginScreen extends BcoreConfig {

	public function __construct() {
		add_filter('login_headerurl', array($this, 'bcore_login_logo_url'));
		add_action('login_enqueue_scripts', array($this, 'bcore_login_logo'));
		add_filter('login_headertitle', array($this, 'bcore_login_logo_url_title'));
	}

	public function bcore_login_logo_url() {
		return home_url();
	}

	public function bcore_login_logo_url_title() {
		return get_bloginfo('title');
	}

	public function bcore_login_logo() {
		?>
			<style type="text/css">
				html { background-color: #fff !important; }
				html body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif; background-color: #ECEFF1; }
				#login h1 a, .login h1 a {
					background-image: url(<?php echo get_template_directory_uri(); ?>/login-icon.png);
					background-size: contain;
					-webkit-background-size: contain;
					width: auto;
				}
			</style>
		<?php 
	}
}
