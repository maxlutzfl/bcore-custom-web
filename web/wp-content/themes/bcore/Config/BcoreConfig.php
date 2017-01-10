<?php
/**
 * BcoreConfig.php
 */
namespace Config;

require BCORE_PARENT_CONFIG_DIRECTORY . '/core/BcoreCleanup.php';
require BCORE_PARENT_CONFIG_DIRECTORY . '/core/BcoreGoogleAnalytics.php';
require BCORE_PARENT_CONFIG_DIRECTORY . '/core/BcoreScriptsStyles.php';
require BCORE_PARENT_CONFIG_DIRECTORY . '/core/BcoreThemeChecker.php';
require BCORE_PARENT_CONFIG_DIRECTORY . '/core/BcoreGravityForms.php';
require BCORE_PARENT_CONFIG_DIRECTORY . '/core/BcoreLoginScreen.php';
require BCORE_PARENT_CONFIG_DIRECTORY . '/setup/BcoreCustomizerOptions.php';
require BCORE_PARENT_CONFIG_DIRECTORY . '/setup/BcoreMenus.php';
require BCORE_PARENT_CONFIG_DIRECTORY . '/setup/BcoreShortcodes.php';

class BcoreConfig {

	public function __construct() {
		new Core\BcoreCleanup();
		new Core\BcoreGoogleAnalytics();
		new Core\BcoreScriptsStyles();
		new Core\BcoreThemeChecker();
		new Core\BcoreGravityForms();
		new Core\BcoreLoginScreen();
		new Setup\BcoreCustomizerOptions();
		new Setup\BcoreMenus();
		new Setup\BcoreShortcodes();
	}
}