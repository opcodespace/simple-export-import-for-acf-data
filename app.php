<?php

/*
Plugin Name: Simple Export Import for ACF Data
Description: Exporting and importing data on specific page
Plugin URI: https://www.opcodespace.com
Author: Opcodespace <mehedee@opcodespace.com>
Author URI: https://www.opcodespace.com
Version: 0.1
Text Domain: simple-export-import-for-acf-data
*/

if ( ! defined( 'ABSPATH' ) ) {exit;}

define("SEIP_VIEW_PATH", wp_normalize_path(plugin_dir_path(__FILE__) . "view/"));
define("SEIP_ASSETSURL", plugins_url("assets/", __FILE__));
define('SEIP_PLUGIN_VERSION', '0.1');

include_once 'functions.php';
include_once 'SeipFront.php';
include_once 'SeipExport.php';
include_once 'SeipEnqueue.php';
include_once 'SeipImport.php';
include_once 'SeipOpcodespace.php';

add_action('plugins_loaded', array('SeipFront', 'init'));
add_action('plugins_loaded', array('SeipExport', 'init'));
add_action('plugins_loaded', array('SeipImport', 'init'));
add_action('plugins_loaded', array('SeipEnqueue', 'init'));

