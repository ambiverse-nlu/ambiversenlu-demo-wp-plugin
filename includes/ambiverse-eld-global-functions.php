<?php
/**
 * Globally-accessible functions
 *
 * @link 		https://ambiverse.com
 * @since 		0.9
 *
 * @package		Ambiverse_ELD
 * @subpackage 	Ambiverse_ELD/includes
 */



/**
 * Returns the result of the get_template global function
 */
function ambiverse_eld_get_template( $name ) {
    return Ambiverse_ELD_Globals::get_template( $name );
}


class Ambiverse_ELD_Globals {


    /**
     * Returns the path to a template file
     *
     * Looks for the file in these directories, in this order:
     * 		Current theme
     * 		Parent theme
     * 		Current theme templates folder
     * 		Parent theme templates folder
     * 		This plugin
     *
     * To use a custom list template in a theme, copy the
     * file from public/templates into a templates folder in your
     * theme. Customize as needed, but keep the file name as-is. The
     * plugin will automatically use your custom template file instead
     * of the ones included in the plugin.
     *
     * @param 	string 		$name 			The name of a template file
     * @return 	string 						The path to the template
     */
    public static function get_template( $name ) {
        $template = '';
        $locations[] = "{$name}.php";
        $locations[] = "/templates/{$name}.php";
        /**
         * Filter the locations to search for a template file
         *
         * @param 	array 		$locations 			File names and/or paths to check
         */
        apply_filters( 'ambiverse-eld-template-paths', $locations );
        $template = locate_template( $locations, TRUE );
        if ( empty( $template ) ) {
            $template = plugin_dir_path( dirname( __FILE__ ) ) . 'public/templates/' . $name . '.php';
        }
        return $template;
    } // get_template()



} // class