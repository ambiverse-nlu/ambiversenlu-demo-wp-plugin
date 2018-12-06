<?php
/**
 * Fired during plugin activation
 *
 * @link              https://ambiversenlu.mpi-inf.mpg.de
 * @since             1.0
 * @package           Ambiverse_ELD
 * @subpackage        Ambiverse_ELD/includes
 */
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      0.9
 * @package    Ambiverse_ELD
 * @subpackage Ambiverse_ELD/includes
 * @author     Dragan Milchevski <dragarn@ambiverse.com>
 */
class Ambiverse_ELD_Activator {
    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    0.9
     */
    public static function activate() {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ambiverse-eld-admin.php';

        $opts 		= array();
        $options 	= Ambiverse_ELD_Admin::get_default_options();

        foreach ( $options as $option ) {
            $opts[ $option[0] ] = $option[1];
        }
        //check to see if present already
        if(!get_option('ambiverse-eld-options')) {
            //option not found, add new
            add_option('ambiverse-eld-options', $opts);
        } else {
            //option already in the database
            //so we get the stored value and merge it with default
            $old_op = get_option('ambiverse-eld-options');
            $new_opts = wp_parse_args($old_op, $opts);

            //update it
            update_option('ambiverse-eld-options', $new_opts);
        }


    }
}