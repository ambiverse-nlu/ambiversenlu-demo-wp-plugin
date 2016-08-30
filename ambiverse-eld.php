<?php

/**
 *
 * @link              https://ambiverse.com
 * @since             1.0
 * @package           Ambiverse_eld
 *
 * @wordpress-plugin
 * Plugin Name:       Ambiverse Entity Linking Demo
 * Plugin URI:        http://ambiverse.com/plugins/ambiverse-entitylinking-demo
 * Description:       Displays the capability of the Ambiverse Natural Understanding API in a nice output for the ambiverse.com web page
 * Version:           0.9
 * Author:            Dragan Milchevski
 * Author URI:        http://ambiverse.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ambiverse-eld
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ambiverse-eld-activator.php
 */
function activate_eld_demo() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-ambiverse-eld-activator.php';
    Ambiverse_ELD_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ambiverse-eld-deactivator.php
 */
function deactivate_eld_demo() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-ambiverse-eld-deactivator.php';
    Ambiverse_ELD_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_eld_demo' );
register_deactivation_hook( __FILE__, 'deactivate_eld_demo' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ambiverse-eld.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_eld_demo() {
    $plugin = new Ambiverse_ELD();
    $plugin->run();
}
run_eld_demo();