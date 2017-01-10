<?php
/**
 * BcoreConfig.php
 */
namespace Config;

class BcoreConfig {

	public function __construct() {

		require BCORE_PARENT_CONFIG_DIRECTORY . '/Core/Cleanup.php';
		new Core\Cleanup();

		require BCORE_PARENT_CONFIG_DIRECTORY . '/Core/GoogleAnalytics.php';
		new Core\GoogleAnalytics();

		require BCORE_PARENT_CONFIG_DIRECTORY . '/Core/ScriptsStyles.php';
		new Core\ScriptsStyles();

		require BCORE_PARENT_CONFIG_DIRECTORY . '/Core/ThemeChecker.php';
		new Core\ThemeChecker();

		require BCORE_PARENT_CONFIG_DIRECTORY . '/Core/GravityForms.php';
		new Core\GravityForms();

		require BCORE_PARENT_CONFIG_DIRECTORY . '/Core/LoginScreen.php';
		new Core\LoginScreen();

		require BCORE_PARENT_CONFIG_DIRECTORY . '/Setup/CustomizerOptions.php';
		new Setup\CustomizerOptions();

		require BCORE_PARENT_CONFIG_DIRECTORY . '/Setup/Menus.php';
		new Setup\Menus();

		require BCORE_PARENT_CONFIG_DIRECTORY . '/Setup/Shortcodes.php';
		new Setup\Shortcodes();

		require BCORE_PARENT_CONFIG_DIRECTORY . '/Setup/ThemeSetup.php';
		new Setup\ThemeSetup();

		require BCORE_PARENT_CONFIG_DIRECTORY . '/Setup/Sidebars.php';
		new Setup\Sidebars();
	}
}