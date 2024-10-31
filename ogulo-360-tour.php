<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.ogulo.de/
 * @since             1.0.0
 * @package           Ogulo_360_Tour
 *
 * @wordpress-plugin
 * Plugin Name:       Ogulo - 360° Tour
 * Plugin URI:        https://wordpress.org/plugins/ogulo-360-tour
 * Description:       With the Ogulo Plugin you can easily integrate your tours into Wordpress.
 * Version:           1.0.10
 * Author:            Ogulo
 * Author URI:        https://www.ogulo.de/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ogulo-360-tour
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}


define('OGULO_360_TOUR_VERSION', '1.0.10');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ogulo-360-tour-activator.php
 */
function activate_ogulo_360_tour()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-ogulo-360-tour-activator.php';
    Ogulo_360_Tour_Activator::activate();

    // add provider key check
    add_option('ogulo_provider', '255', '', 'ogulo');

    // set rewrite
    set_transient('Ogulo_flush', 1, 60);
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ogulo-360-tour-deactivator.php
 */
function deactivate_ogulo_360_tour()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-ogulo-360-tour-deactivator.php';
    Ogulo_360_Tour_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_ogulo_360_tour');
register_deactivation_hook(__FILE__, 'deactivate_ogulo_360_tour');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-ogulo-360-tour.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ogulo_360_tour()
{
    global $ogulo360;
    $ogulo360 = $plugin = new Ogulo_360_Tour();
    $ogulo360->dirpath = plugin_dir_path(__FILE__);
    $ogulo360->urlpath = plugin_dir_url(__FILE__);
    $plugin->run();
}
run_ogulo_360_tour();
