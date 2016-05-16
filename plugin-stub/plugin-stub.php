<?php
/**
 * Plugin Name: Plugin Stub
 * Description: OOP based example plugin stub.
 * Author: Author Name
 * Author URI: http://www.example.com/about/author-name/
 * Plugin URI: http://www.example.com/about/plugin-name/
 * Version: 1.0.0
 */
define('__PLUGIN_STUB_ROOT__', dirname(__FILE__));
require_once(__PLUGIN_STUB_ROOT__.'/classes/class.plugin_stub.inc.php');

$plugin_stub = new plugin_stub();
$plugin_stub->init(basename(dirname(__FILE__)).'/'.basename(__FILE__));
