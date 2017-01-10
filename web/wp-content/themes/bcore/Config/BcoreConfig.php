<?php
/**
 * BcoreConfig.php
 */
namespace Config;

require BCORE_PARENT_CONFIG_DIRECTORY . '/Core/BcoreCleanup.php';
require BCORE_PARENT_CONFIG_DIRECTORY . '/Core/BcoreGoogleAnalytics.php';
require BCORE_PARENT_CONFIG_DIRECTORY . '/Core/BcoreScriptsStyles.php';

class BcoreConfig {

	public function __construct() {
		new Core\BcoreCleanup();
		new Core\BcoreGoogleAnalytics();
		new Core\BcoreScriptsStyles();
	}
}