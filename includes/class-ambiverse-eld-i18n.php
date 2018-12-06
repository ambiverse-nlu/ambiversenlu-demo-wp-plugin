<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://ambiversenlu.mpi-inf.mpg.de
 * @since      0.9
 *
 * @package    Ambiverse_ELD
 * @subpackage Ambiverse_ELD/includes
 */
/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      0.9
 * @package    Ambiverse_ELD
 * @subpackage Ambiverse_ELD/includes
 *
 */
class Ambiverse_ELD_i18n {
    /**
     * Load the plugin text domain for translation.
     *
     * @since    0.9
     */
    public function load_plugin_textdomain() {
        load_plugin_textdomain(
            'plugin-name',
            false,
            dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
        );
    }
}