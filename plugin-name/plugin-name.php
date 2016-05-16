<?php
/**
 * Plugin Name: Plugin Name
 * Description: Works as OOP based plugin stub: Just copy the scripts and start coding on it.
 * Author: Author Name
 * Author URI: http://www.example.com/about/author-name/
 * Plugin URI: http://www.example.com/about/plugin-name/
 * Version: 1.0.0
 */
define('__PLUGIN_NAME_ROOT__', dirname(__FILE__));
require_once(__PLUGIN_NAME_ROOT__.'/classes/class.plugin_name.inc.php');

$plugin_name = new plugin_name();
$plugin_name->init(basename(dirname(__FILE__)).'/'.basename(__FILE__));
