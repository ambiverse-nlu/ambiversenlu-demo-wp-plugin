<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://ambiversenlu.mpi-inf.mpg.de
 * @since      0.9
 *
 * @package    Ambiverse_ELD
 * @subpackage Ambiverse_ELD/public
 */
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the methods for creating the templates.
 *
 * @package    Ambiverse_ELD
 * @subpackage Ambiverse_ELD/public
 *
 */
class Ambiverse_ELD_Template_Functions
{
    /**
     * Private static reference to this class
     * Useful for removing actions declared here.
     *
     * @var    object $_this
     */
    private static $_this;

    /**
     * The ID of this plugin.
     *
     * @since    0.9
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;
    /**
     * The version of this plugin.
     *
     * @since    0.9
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    0.9
     * @param      string $plugin_name The name of the plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        self::$_this = $this;
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Includes the form start template file
     *
     * @hooked ambiverse-eld-before-content
     *
     */
    public function content_form_start( ) {
        include ambiverse_eld_get_template( 'ambiverse-eld-public-form-start' );
    }

    /**
     * Includes the form start template file
     *
     * @hooked ambiverse-eld-after-content
     *
     */
    public function content_form_end( ) {
        include ambiverse_eld_get_template( 'ambiverse-eld-public-form-end' );
    }

    /**
     * Includes the textarea template file
     *
     * @hooked ambiverse-eld-content
     *
     */
    public function content_textarea( $args ) {
        include ambiverse_eld_get_template( 'ambiverse-eld-public-textarea' );
    }

    /**
     * Includes the textarea template file
     *
     * @hooked ambiverse-eld-button
     *
     */
    public function analyze_button( ) {
        include ambiverse_eld_get_template( 'ambiverse-eld-public-button' );
    }

    /**
     * Includes the json output template file
     *
     * @hooked ambiverse-eld-json-output
     *
     */
    public function json_output() {
        $args["id"] = "ambiverse-json-output";
        include ambiverse_eld_get_template( 'ambiverse-eld-public-json-output' );
    }

    /**
     * Includes the json output template file
     *
     * @hooked ambiverse-eld-json-output-meta
     *
     */
    public function json_output_meta() {
        $args["id"] = "ambiverse-json-output-meta";
        include ambiverse_eld_get_template( 'ambiverse-eld-public-json-output' );
    }

    /**
     * Includes the tab output template file
     *
     * @hooked ambiverse-eld-public-tab
     *
     */
    public function tab_output( ) {
        include ambiverse_eld_get_template( 'ambiverse-eld-public-tab' );
    }

    /**
     * Includes the pis output template file
     *
     * @hooked ambiverse-eld-public-pills
     *
     */
    public function pills_output( ) {
        include ambiverse_eld_get_template( 'ambiverse-eld-public-pills' );
    }

    /**
     * Includes the json output template file
     *
     * @hooked ambiverse-eld-public-text-result
     *
     */
    public function text_result( ) {
        include ambiverse_eld_get_template( 'ambiverse-eld-public-text-result' );
    }

    /**
     * Includes the entity loop view template file
     *
     * @hooked ambiverse-eld-public-entities
     *
     */
    public function entities_holder() {
        include ambiverse_eld_get_template( 'ambiverse-eld-public-entities' );
    }

    /**
     * Includes the open facts loop view template file
     *
     * @hooked ambiverse-eld-open-facts
     *
     */
     public function open_facts_holder() {
         include ambiverse_eld_get_template( 'ambiverse-eld-open-facts' );
     }

    /**
     * Includes the settings
     *
     * @hooked ambiverse-eld-public-settings
     *
     */
    public function settings_output( $args ) {
        include ambiverse_eld_get_template( 'ambiverse-eld-public-settings' );
    }

}