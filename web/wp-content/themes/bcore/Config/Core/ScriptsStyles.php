<?php 
/**
 * ScriptsStyles.php
 */
namespace Config\Core;
use Config\BcoreConfig;

class ScriptsStyles extends BcoreConfig {

	public function __construct() {
		add_action('wp_enqueue_scripts', array($this, 'frontend'));
		add_action('admin_enqueue_scripts', array($this, 'backend'));
	}

	public function frontend() {
		// Deregister
		wp_deregister_script('jquery');
		wp_deregister_script('wp-embed');

		// Register
		wp_register_script('bcore-jquery', BCORE_PARENT_RESOURCES_DIRECTORY . 'scripts/jquery-3.1.1.min.js', array(), '3.1.1', true);

		// Enqueue
		// wp_enqueue_script('bcore-jquery');
	}

	public function backend() {

	}
} 
