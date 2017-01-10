<?php 
/**
 * BcoreGravityForms.php
 */
namespace Config\Core;
use Config\BcoreConfig;

class BcoreGravityForms extends BcoreConfig {

	public function __construct() {
		add_filter('wp_mail_from', array($this, 'new_mail_from'));
		add_filter('wp_mail_from_name', array($this, 'new_mail_from_name'));
		add_filter('gform_notification', array($this, 'gform_notification_signature'), 10, 3);
		add_filter('gform_validation_message', array($this, 'gravity_forms_error_message'), 10, 2);
		add_filter('gform_tabindex', array($this, 'gform_tabindexer'), 10, 2);
	}

	 
	public function new_mail_from($old) {
		return get_option('admin_email');
	}

	public function new_mail_from_name($old) {
		return get_option('blogname');
	}

	
	public function gform_notification_signature($notification, $form, $entry) {
		$email = get_option('admin_email');
		$title = get_option('blogname');
		$notification['message'] .= "<br><br> Message sent from " . $title . " @ " . $email;
		return $notification;
	}

	
	public function gravity_forms_error_message($message, $form) {
		return "<div class='validation_error'>There was a problem, please fill in the required fields.</div>";
	}

	
	public function gform_tabindexer($tab_index, $form = false) {
		$starting_index = 1000;
		if ($form) {
			add_filter( 'gform_tabindex_' . $form['id'], 'gform_tabindexer' );
		}
		return \GFCommon::$tab_index >= $starting_index ? \GFCommon::$tab_index : $starting_index;
	}
}