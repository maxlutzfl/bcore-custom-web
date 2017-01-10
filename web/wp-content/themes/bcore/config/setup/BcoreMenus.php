<?php 
/**
 * BcoreMenus.php
 */
namespace Config\Setup;
use Config\BcoreConfig;

class BcoreMenus extends BcoreConfig {
		
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