<?php 
/**
 * Menus.php
 */
namespace Config\Setup;
use Config\BcoreConfig;

class Menus extends BcoreConfig {
		
	public function __construct() {
		
		register_nav_menus(
			array(
				'primary' => 'Primary Navigation',
				'mobile' => 'Mobile Menu',
				'footer' => 'Footer Links'
			)
		);
	}
}