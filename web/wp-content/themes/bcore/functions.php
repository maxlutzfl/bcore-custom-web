<?php
/**
 * functons.php
 * @package bcore
 */

/** Define constants */
define('BCORE_PARENT_DIRECTORY_NAME', 'bcore');
define('BCORE_PARENT_BASE_DIRECTORY', get_template_directory());
define('BCORE_PARENT_CONFIG_DIRECTORY', get_template_directory() . '/Config');
define('BCORE_PARENT_RESOURCES_DIRECTORY', get_template_directory_uri() . '/Resources');

/** Initilize theme config */
require BCORE_PARENT_CONFIG_DIRECTORY . '/BcoreConfig.php';
new Config\BcoreConfig();
