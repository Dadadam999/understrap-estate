<?php

/**
 * Plugin Name: Understrap estate
 * Description: Plugin for managing real estate ads.
 * Requires PHP: 8.2
 * Requires at least: 6.0
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html 
 * Version: 0.0.1
 */

require_once __DIR__ . '/vendor/autoload.php';

use Vendor\UnderstrapEstate\Main;

add_action('plugins_loaded', function () {
    load_plugin_textdomain(
        'understrap-estate-plugin',
        false,
        '/understrap-estate/languages/'
    );

    new Main();
});
