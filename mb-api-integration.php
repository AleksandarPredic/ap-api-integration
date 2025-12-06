<?php
/**
 * Plugin Name: AP API integration
 * Description: Integration with the API to get the data from the internal system
 * Version: 1.0.0
 * Author: Aleksandar Predic, aleksandar.predic@gmail.com
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

// Autoload vendors
require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

add_action('plugins_loaded', function () {
    \ApApi\PluginInit::getInstance()->setInstances();
});
