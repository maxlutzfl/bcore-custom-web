<?php
/*
Plugin Name: bcore PHP console_log()
Plugin URI: https://brandco.com/
Description: In PHP, use the function <code>console_log($object)</code> and check the Chrome Dev Tools console.
Version: 1.0.0
Author: BrandCo LLC
Author URI: https://brandco.com/
*/

/**
 * Load script
 */
add_action('wp_enqueue_scripts', 'var_dump_console_log_files', 1000);
function var_dump_console_log_files() {
	wp_register_script('bcore-php-console-log', plugins_url('bcore-php-console-log.js', __FILE__), array(), '1.0.0', true);
}

/**
 * The function
 */
function console_log($data) {
	echo '<div data-console-log="' . htmlspecialchars(json_encode($data)) . '"></div>';

	if ( ! wp_script_is('bcore-php-console-log', 'enqueued') ) {
		wp_enqueue_script('bcore-php-console-log');
	}
}
