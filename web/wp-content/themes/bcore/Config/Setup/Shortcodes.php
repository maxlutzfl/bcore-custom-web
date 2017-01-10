<?php
/**
 * Shortcodes.php
 */
namespace Config\Setup;
use Config\BcoreConfig;

class Shortcodes extends BcoreConfig {

	public function __construct() {
		add_shortcode('button_shortcode', array($this, 'bcore_button_shortcode'));
		add_shortcode('container', array($this, 'bcore_container_shortcode'));
		add_shortcode('half', array($this, 'bcore_half_shortcode'));
		add_filter('the_content', array($this, 'bcore_shortcode_empty_paragraph_fix'));
	}

	function bcore_button_shortcode($attributes) {
		$link = (isset($attributes['link'])) ? $attributes['link'] : '';
		$title = (isset($attributes['title'])) ? $attributes['title'] : 'Click here to learn more';
		$style = (isset($attributes['style'])) ? $attributes['style'] : '';
		if ( $link ) {
			return '<p><a href="' . $link . '" class="button-style button-style-' . $style . ' ' . $arrows . '"><span>' . $title . '</span></a></p>';
		}
	}

	function bcore_container_shortcode($atts, $content = null) {
		return '<div class="content-container">' . apply_filters('the_content', $content) . '</div>';
	}
	
	function bcore_half_shortcode($atts, $content = null) {
		return '<div class="content-column__half">' . apply_filters('the_content', $content) . '</div>';
	}

	function bcore_shortcode_empty_paragraph_fix($content) {
		$array = array(
			'<p>['    => '[',
			']</p>'   => ']',
			']<br />' => ']'
		);
		return strtr( $content, $array );
	}	
}
