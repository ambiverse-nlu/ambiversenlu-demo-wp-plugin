<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://ambiverse.com
 * @since      0.9
 *
 * @package    Ambiverse_ELD
 * @subpackage Ambiverse_ELD/includes
 */
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      0.9
 * @package    Ambiverse_ELD
 * @subpackage Ambiverse_ELD/includes
 * @author     Dragan Milchevski <dragan@ambiverse.com>
 */
class Ambiverse_ELD {
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    0.9
     * @access   protected
     * @var      Ambiverse_ELD_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;
    /**
     * The unique identifier of this plugin.
     *
     * @since    0.9
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;
    /**
     * The current version of the plugin.
     *
     * @since    0.9
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Sanitizer for cleaning user input
     *
     * @since    0.9
     * @access   private
     * @var      Ambiverse_ELD_Sanitize    $sanitizer    Sanitizes data
     */
    private $sanitizer;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    0.9
     */
    public function __construct() {
        $this->plugin_name = 'ambiverse-eld';
        $this->version = '0.9';
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $this->define_template_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Ambiverse_ELD_Loader. Orchestrates the hooks of the plugin.
     * - Ambiverse_ELD_i18n. Defines internationalization functionality.
     * - Ambiverse_ELD_Admin. Defines all hooks for the admin area.
     * - Ambiverse_ELD_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    0.9
     * @access   private
     */
    private function load_dependencies() {
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ambiverse-eld-loader.php';
        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ambiverse-eld-i18n.php';
        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ambiverse-eld-admin.php';
        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-ambiverse-eld-public.php';

        /**
         *  The class responsible for defining all actions creating the templates.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-ambiverse-eld-template-functions.php';

        /**
         * The class responsible for all global functions.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/ambiverse-eld-global-functions.php';

        /**
         * The class responsible for sanitizing user input
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ambiverse-eld-sanitize.php';

        /**
         * The class for the Ambiverse API
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ambiverse-api.php';

        $this->loader = new Ambiverse_ELD_Loader();
        $this->sanitizer = new Ambiverse_ELD_Sanitize();
    }
    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Plugin_Name_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    0.9
     * @access   private
     */
    private function set_locale() {
        $plugin_i18n = new Ambiverse_ELD_i18n();
        $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
    }
    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    0.9
     * @access   private
     */
    private function define_admin_hooks() {
        $plugin_admin = new Ambiverse_ELD_Admin( $this->get_plugin_name(), $this->get_version() );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_admin_menu');
        $this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'register_sections' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'register_fields' );
    }
    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    0.9
     * @access   private
     */
    private function define_public_hooks() {
        $plugin_public = new Ambiverse_ELD_Public( $this->get_plugin_name(), $this->get_version() );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

        $this->loader->add_action( 'init', $plugin_public, 'register_shortcodes' );

        /**
         * Action instead of template tag.
         *
         * do_action( 'ambiverse-eld' );
         *
         * @link 	http://nacin.com/2010/05/18/rethinking-template-tags-in-plugins/
         */
        $this->loader->add_action( 'ambiverse-eld', $plugin_public, 'render_demo' );
        $this->loader->add_action( 'wp_ajax_tag_analyze_document', $plugin_public, 'analyze_document_ajax_handler' );
        $this->loader->add_action( 'wp_ajax_tag_entity_metadata', $plugin_public, 'entity_metatada_ajax_handler' );


    }

    /**
     * Register all of the hooks related to the templates.
     *
     * @since    0.9
     * @access   private
     */
    private function define_template_hooks() {

        $plugin_templates = new Ambiverse_ELD_Template_Functions( $this->get_plugin_name(), $this->get_version() );


        $this->loader->add_action( 'ambiverse-eld-before-content', $plugin_templates, 'content_form_start');
        $this->loader->add_action( 'ambiverse-eld-content', $plugin_templates, 'content_textarea');
        $this->loader->add_action( 'ambiverse-eld-button', $plugin_templates, 'analyze_button');
        $this->loader->add_action( 'ambiverse-eld-after-content', $plugin_templates, 'content_form_end');

        $this->loader->add_action( 'ambiverse-eld-json-output', $plugin_templates, 'json_output');
        $this->loader->add_action( 'ambiverse-eld-json-output-meta', $plugin_templates, 'json_output_meta');
        $this->loader->add_action( 'ambiverse-eld-public-tab', $plugin_templates, 'tab_output');
        $this->loader->add_action( 'ambiverse-eld-public-pills', $plugin_templates, 'pills_output');
        $this->loader->add_action( 'ambiverse-eld-public-text-result', $plugin_templates, 'text_result');

        $this->loader->add_action( 'ambiverse-eld-public-entities', $plugin_templates, 'entities_holder');
        $this->loader->add_action( 'ambiverse-eld-public-settings', $plugin_templates, 'settings_output');

    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    0.9
     */
    public function run() {
        $this->loader->run();
    }
    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     0.9
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }
    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     0.9
     * @return    Ambiverse_ELD_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }
    /**
     * Retrieve the version number of the plugin.
     *
     * @since     0.9
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }
}