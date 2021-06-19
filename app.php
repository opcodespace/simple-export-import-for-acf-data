<?php

/*
Plugin Name: Simple Export Import Page
Description: Exporting and importing data on specific page
Plugin URI: https://www.opcodespace.com
Author: Opcodespace <mehedee@opcodespace.com>
Author URI: https://www.opcodespace.com
Version: 0.1
Text Domain: simple-export-import-page
*/

if ( ! defined( 'ABSPATH' ) ) {exit;}

define("SEIP_VIEW_PATH", wp_normalize_path(plugin_dir_path(__FILE__) . "view/"));

include_once 'SeipFront.php';
include_once 'SeipExport.php';
include_once 'SeipImport.php';

add_action('plugins_loaded', array('SeipFront', 'init'));
add_action('plugins_loaded', array('SeipExport', 'init'));
add_action('plugins_loaded', array('SeipImport', 'init'));

