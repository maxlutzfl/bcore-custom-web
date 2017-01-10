<?php 
/**
 * Sidebars.php
 */
namespace Config\Setup;
use Config\BcoreConfig;

class Sidebars extends BcoreConfig {
		
	public function __construct() {
		add_action('widgets_init', array($this, 'widgets'));
	}

	function widgets() {
		register_sidebar(
			array(
				'name' => 'Sidebar',
				'id' => 'main-sidebar',
				'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h1 class="widget-title">',
				'after_title' => '</h1>',
			)
		);
	}
}