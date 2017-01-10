<?php
/**
 * BcoreConfig.php
 */
namespace Config;

class BcoreConfig {

	public function __construct() {

		require_once BCORE_PARENT_CONFIG_DIRECTORY . '/Core/Cleanup.php';
		new Core\Cleanup();

		require_once BCORE_PARENT_CONFIG_DIRECTORY . '/Core/GoogleAnalytics.php';
		new Core\GoogleAnalytics();

		require_once BCORE_PARENT_CONFIG_DIRECTORY . '/Core/ScriptsStyles.php';
		new Core\ScriptsStyles();

		require_once BCORE_PARENT_CONFIG_DIRECTORY . '/Core/ThemeChecker.php';
		new Core\ThemeChecker();

		require_once BCORE_PARENT_CONFIG_DIRECTORY . '/Core/GravityForms.php';
		new Core\GravityForms();

		require_once BCORE_PARENT_CONFIG_DIRECTORY . '/Core/LoginScreen.php';
		new Core\LoginScreen();

		require_once BCORE_PARENT_CONFIG_DIRECTORY . '/Setup/CustomizerOptions.php';
		new Setup\CustomizerOptions();

		require_once BCORE_PARENT_CONFIG_DIRECTORY . '/Setup/Menus.php';
		new Setup\Menus();

		require_once BCORE_PARENT_CONFIG_DIRECTORY . '/Setup/Shortcodes.php';
		new Setup\Shortcodes();

		require_once BCORE_PARENT_CONFIG_DIRECTORY . '/Setup/ThemeSetup.php';
		new Setup\ThemeSetup();

		require_once BCORE_PARENT_CONFIG_DIRECTORY . '/Setup/Sidebars.php';
		new Setup\Sidebars();

		require_once BCORE_PARENT_CONFIG_DIRECTORY . '/Functions/ThemeFunctions.php';
	}
}