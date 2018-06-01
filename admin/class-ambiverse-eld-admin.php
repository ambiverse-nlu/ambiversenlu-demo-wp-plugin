<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://ambiverse.com
 * @since      0.9
 *
 * @package    Ambiverse_ELD
 * @subpackage Ambiverse_ELD/admin
 */
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ambiverse_ELD
 * @subpackage Ambiverse_ELD/admin
 * @author     Dragan Milchevski <dragan@ambiverse.com>
 */
class Ambiverse_ELD_Admin
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;
    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * The plugin options.
     *
     * @since 		1.0.0
     * @access 		private
     * @var 		string 			$options    The plugin options.
     */
    private $options;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of this plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        $this->set_options();
    }


    /**
     * Register the stylesheets for the admin area.
     *
     * @since    0.9
     */
    public function enqueue_styles()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Ambiverse_EntityLinking_Demo_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Plugin_Name_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/ambiverse-eld-admin.css', array(), $this->version, 'all');

        wp_register_style('jquery-ui', '//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css');
        wp_enqueue_style( 'jquery-ui' );
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    0.9
     */
    public function enqueue_scripts()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Ambiverse_EntityLinking_Demo_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Ambiverse_EntityLinking_Demo_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        //wp_register_script( $handle, $src, $deps, $ver, $in_footer );
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-slider');
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/ambiverse-eld-admin.js', array('jquery'), $this->version, false);
    }

    /**
     * Sets the class variable $options
     *
     * @since    0.9
     */
    private function set_options() {
        $this->options = get_option( $this->plugin_name . '-options' );
    }

    /**
     * Register the settings menu for this plugin into the WordPress Settings menu.
     *
     * @since 0.9
     */
    public function add_admin_menu()
    {

        //add_options_page( __( 'Ambiverse Entity Linking Demo Settings', 'ambiverse-eld' ), __( 'Ambiverse Entity Linking Demo', 'ambiverse-eld' ), 'manage_options', $this->plugin_name, array( $this, 'ambiverse_eld_options' ) );

        // Submenu Page
        // add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function);

        add_submenu_page(
            'options-general.php',
            apply_filters($this->plugin_name . '-settings-page-title', esc_html__('Ambiverse Entity Linking Demo', 'ambiverse-eld')),
            apply_filters($this->plugin_name . '-settings-menu-title', esc_html__('Ambiverse Entity Linking Demo Settings', 'ambiverse-eld')),
            'manage_options',
            $this->plugin_name . '-settings',
            array($this, 'page_options')
        );

    }


    /**
     * Render the settings page for this plugin.
     *
     * @since 0.9
     */
    public function page_options()
    {
        include(plugin_dir_path(__FILE__) . 'partials/ambiverse-eld-admin-page-settings.php');
    }

    /**
     * Registers settings sections with WordPress
     *
     * @since 0.9
     */
    public function register_sections()
    {
        // add_settings_section( $id, $title, $callback, $menu_slug );

        add_settings_section(
            $this->plugin_name . '-auth-settings',
            apply_filters($this->plugin_name . 'section-title-settings-auth', esc_html__('Authentication Settings', 'ambiverse-eld')),
            array($this, 'section_auth'),
            $this->plugin_name
        );

        add_settings_section(
            $this->plugin_name . '-settings-disambiguation',
            apply_filters($this->plugin_name . 'section-title-settings-disambiguation', esc_html__('Disambiguation Settings', 'ambiverse-eld')),
            array($this, 'section_disambiguation'),
            $this->plugin_name
        );

        add_settings_section(
            $this->plugin_name . '-settings-layout',
            apply_filters($this->plugin_name . 'section-title-settings-layout', esc_html__('Layout', 'ambiverse-eld')),
            array($this, 'section_layout'),
            $this->plugin_name
        );

        add_settings_section(
            $this->plugin_name . '-usage',
            apply_filters($this->plugin_name . 'section-title-settings-disambiguation', esc_html__('Usage', 'ambiverse-eld')),
            array($this, 'section_usage'),
            $this->plugin_name
        );
    }

    /**
     * Registers plugin settings
     *
     * @since        0.9
     * @return        void
     */
    public function register_settings()
    {
        // register_setting( $option_group, $option_name, $sanitize_callback );
        register_setting(
            $this->plugin_name . '-options',
            $this->plugin_name . '-options'
            ,array($this, 'validate_options')
        );
    }

    /**
     * Creates a text field
     *
     * @param 	array 		$args 			The arguments for the field
     * @return 	string 						The HTML field
     */
    public function field_text( $args ) {
        $defaults['class'] 			= 'text widefat';
        $defaults['description'] 	= '';
        $defaults['label'] 			= '';
        $defaults['name'] 			= $this->plugin_name . '-options[' . $args['id'] . ']';
        $defaults['placeholder'] 	= '';
        $defaults['type'] 			= 'text';
        $defaults['value'] 			= '';
        apply_filters( $this->plugin_name . '-field-text-options-defaults', $defaults );
        $atts = wp_parse_args( $args, $defaults );
        if ( ! empty( $this->options[$atts['id']] ) ) {
            $atts['value'] = $this->options[$atts['id']];
        }

        include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-text.php' );
    } // field_text()

    /**
     * Creates a checkbox field
     *
     * @param 	array 		$args 			The arguments for the field
     * @return 	string 						The HTML field
     */
    public function field_checkbox( $args ) {

        if(isset($args[0]) && is_array($args[0])) {
            //Multiple check boxes for one label
            //echo "EBI SEE";
            foreach ($args as $value) {
                $defaults['class'] = '';
                $defaults['description'] = '';
                $defaults['label'] = '';
                $defaults['name'] = $this->plugin_name . '-options[' . $value['id'] . ']';
                $defaults['value'] = '';
                apply_filters($this->plugin_name . '-field-checkbox-options-defaults', $defaults);
                $atts = wp_parse_args($value, $defaults);

                if (!empty($this->options[$atts['id']])) {
                   // echo "ID: ".$atts['id']." VALUE: ".$this->options[$atts['id']];
                    $atts['value'] = $this->options[$atts['id']];
                }

                include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-checkbox.php' );
            }
        } else {

            $defaults['class'] = '';
            $defaults['description'] = '';
            $defaults['label'] = '';
            $defaults['name'] = $this->plugin_name . '-options[' . $args['id'] . ']';
            $defaults['value'] = '';
            apply_filters($this->plugin_name . '-field-checkbox-options-defaults', $defaults);
            $atts = wp_parse_args($args, $defaults);
            //var_dump($this->options);
            if (!empty($this->options[$atts['id']])) {
                $atts['value'] = $this->options[$atts['id']];
            }

            include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-checkbox.php' );
        }

    } // field_checkbox()


    /**
     * Creates a checkbox field
     *
     * @param 	array 		$args 			The arguments for the field
     * @return 	string 						The HTML field
     */
    public function field_image_radio_button( $args ) {

        if(isset($args[0]) && is_array($args[0])) {
            //Multiple check boxes for one label
            foreach ($args as $value) {
                $defaults['class'] = '';
                $defaults['description'] = '';
                $defaults['label'] = '';
                $defaults['img'] = '';
                $defaults['name'] = '';
                $defaults['value'] = 0;
                apply_filters($this->plugin_name . '-field-image-radio-options-defaults', $defaults);
                $atts = wp_parse_args($value, $defaults);
                $atts['name'] = $this->plugin_name . '-options['.$atts['name'].']';

                if (!empty($this->options[$value['name']])) {
                    $atts['selected'] = $this->options[$value['name']];
                }
                include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-image-button.php' );
            }
        } else {

            $defaults['class'] = '';
            $defaults['description'] = '';
            $defaults['label'] = '';
            $defaults['img'] = '';
            $defaults['name'] = '';
            $defaults['value'] = 0;
            apply_filters($this->plugin_name . '-field-image-radio-options-defaults', $defaults);
            $atts = wp_parse_args($args, $defaults);
            $atts['name'] = $this->plugin_name . '-options['.$atts['name'].']';

            if (!empty($this->options[$atts['name']])) {
                $atts['value'] = $this->options[$atts['name']];
            }
            include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-image-button.php' );
        }

    } // field_image_radio_button()

    /**
     * Creates a range slider field
     *
     * @param 	array 		$args 			The arguments for the field
     * @return 	string 						The HTML field
     */
    public function field_slider( $args ) {
        $defaults['class'] 			= 'slider';
        $defaults['description'] 	= '';
        $defaults['label'] 			= '';
        $defaults['name'] 			= $this->plugin_name . '-options[' . $args['id'] . ']';
        $defaults['placeholder'] 	= '';
        $defaults['type'] 			= 'text';
        $defaults['value'] 			= '';
        $defaults['slider-id'] 			= '';
        $defaults['slider-min'] 			= '';
        $defaults['slider-max'] 			= '';
        $defaults['slider-step'] 			= '';
        $defaults['slider-value'] 			= '';

        apply_filters( $this->plugin_name . '-field-slider-options-defaults', $defaults );
        $atts = wp_parse_args( $args, $defaults );

        if ( ! empty( $this->options[$atts['id']] ) ) {
            $atts['slider-value']  = $this->options[$atts['id']];
            $atts['value']  = $this->options[$atts['id']];
        }

        include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-slider.php' );
    } // field_text()


    /**
     * Creates a select field
     *
     * Note: label is blank since its created in the Settings API
     *
     * @param 	array 		$args 			The arguments for the field
     * @return 	string 						The HTML field
     */
    public function field_select( $args ) {
        $defaults['aria'] 			= '';
        $defaults['blank'] 			= '';
        $defaults['class'] 			= '';
        $defaults['context'] 		= '';
        $defaults['description'] 	= '';
        $defaults['label'] 			= '';
        $defaults['name'] 			= $this->plugin_name . '-options[' . $args['id'] . ']';
        $defaults['selections'] 	= array();
        $defaults['value'] 			= '';
        apply_filters( $this->plugin_name . '-field-select-options-defaults', $defaults );
        $atts = wp_parse_args( $args, $defaults );
        if ( ! empty( $this->options[$atts['id']] ) ) {
            $atts['value'] = $this->options[$atts['id']];
        }
        if ( empty( $atts['aria'] ) && ! empty( $atts['description'] ) ) {
            $atts['aria'] = $atts['description'];
        } elseif ( empty( $atts['aria'] ) && ! empty( $atts['label'] ) ) {
            $atts['aria'] = $atts['label'];
        }
        include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-select.php' );
    } // field_select()

    /**
     * Registers settings fields with WordPress
     */
    public function register_fields() {

        // add_settings_field( $id, $title, $callback, $menu_slug, $section, $args );

        //var_dump($this->options);

        add_settings_field(
            'ambiverse-settings-client-id',
            apply_filters( $this->plugin_name . 'label-settings-client-id', esc_html__( 'Client ID', 'ambiverse-eld' ) ),
            array( $this, 'field_text' ),
            $this->plugin_name,
            $this->plugin_name . '-auth-settings',
            array(
                'description' 	=> 'Add your Client ID for your Ambiverse NLU API.',
                'id' 			=> 'settings-client-id',
                'value' 		=> '',
            )
        );

        add_settings_field(
            'ambiverse-settings-client-secret',
            apply_filters( $this->plugin_name . 'label-settings-client-secret', esc_html__( 'Client Secret', 'ambiverse-eld' ) ),
            array( $this, 'field_text' ),
            $this->plugin_name,
            $this->plugin_name . '-auth-settings',
            array(
                'selections' 	=> 'Add your Client Secret for your Ambiverse NLU API.',
                'id' 			=> 'settings-client-secret',
                'value' 		=> '',
            )
        );

        add_settings_field(
            'ambiverse-settings-api-version',
            apply_filters( $this->plugin_name . 'label-settings-api-version', esc_html__( 'API Version', 'ambiverse-eld' ) ),
            array( $this, 'field_select' ),
            $this->plugin_name,
            $this->plugin_name . '-auth-settings',
            array(
                'id' => 'settings-api-version',
                'selections' 	=> array(
                    0 => array(
                        'value' => 'v1',
                        'label' =>  'v1',
                    ),
                    1 => array(
                        'value' => 'v2',
                        'label' =>  'v2',
                    ),
                )
            )
        );


        add_settings_field(
            'ambiverse-settings-api-endpoind',
            apply_filters( $this->plugin_name . 'label-settings-api-endpoint', esc_html__( 'API Endpoint', 'ambiverse-eld' ) ),
            array( $this, 'field_select' ),
            $this->plugin_name,
            $this->plugin_name . '-auth-settings',
            array(
                'id' => 'settings-api-endpoint',
                'selections' 	=> array(
                    0 => array(
                        'value' => 'api',
                        'label' =>  'api',
                    ),
                    1 => array(
                        'value' => 'api-staging',
                        'label' =>  'api-staging',
                    ),
                )
            )
        );

         add_settings_field(
                    'ambiverse-settings-api-method',
                    apply_filters( $this->plugin_name . 'label-settings-api-method', esc_html__( 'API Method', 'ambiverse-eld' ) ),
                    array( $this, 'field_select' ),
                    $this->plugin_name,
                    $this->plugin_name . '-auth-settings',
                    array(
                        'id' => 'settings-api-method',
                        'selections' 	=> array(
                            0 => array(
                                'value' => '/entitylinking/',
                                'label' =>  'entitylinking',
                            ),
                            1 => array(
                                'value' => '/factextraction/',
                                'label' =>  'factextraction',
                            ),
                        )
                    )
                );

        add_settings_field(
            'ambiverse-settings-language-settings',
            apply_filters( $this->plugin_name . 'label-settings-language', esc_html__( 'Languages', 'ambiverse-eld' ) ),
            array( $this, 'field_checkbox' ),
            $this->plugin_name,
            $this->plugin_name . '-settings-disambiguation',
            array(
                0 => array(
                    'description' 	=> 'English',
                    'id' 			=> 'settings-language-en',
                    'value' 		=> 'en'
                ),
                1 => array(
                    'description' 	=> 'German',
                    'id' 			=> 'settings-language-de',
                    'value' 		=> 'de'
                ),
                2 => array(
                    'description' 	=> 'Spanish',
                    'id' 			=> 'settings-language-es',
                    'value' 		=> 'es'
                ),
                3 => array(
                    'description' 	=> 'Chinese',
                    'id' 			=> 'settings-language-zh',
                    'value' 		=> 'zh'
                )
            )
        );

        add_settings_field(
            'ambiverse-settings-coherent-settings',
            apply_filters( $this->plugin_name . 'label-settings-coherent', esc_html__( 'Is document coherent?', 'ambiverse-eld' ) ),
            array( $this, 'field_checkbox' ),
            $this->plugin_name,
            $this->plugin_name . '-settings-disambiguation',
            array(

                'description' 	=> 'Entities are related to each other',
                'id' 			=> 'settings-coherent-document',
                'value' 		=> '1'

            )
        );

        add_settings_field(
            'ambiverse-settings-threshold-settings',
            apply_filters( $this->plugin_name . 'label-settings-threshold', esc_html__( 'Default confidence threshold', 'ambiverse-eld' ) ),
            array( $this, 'field_slider' ),
            $this->plugin_name,
            $this->plugin_name . '-settings-disambiguation',
            array(
                'description' 	=> 'Entities are related to each other',
                'id' 			=> 'settings-threshold-document',
                'value' 		=> '0.075',
                'slider-id' 			=> 'settings-threshold-document-slider',
                'slider-min' 			=> '0',
                'slider-max' 			=> '1',
                'slider-step' 			=> '0.005'

            )
        );


        add_settings_field(
            'ambiverse-settings-images',
            apply_filters( $this->plugin_name . 'label-settings-images', esc_html__( 'Images:', 'ambiverse-eld' ) ),
            array( $this, 'field_checkbox' ),
            $this->plugin_name,
            $this->plugin_name . '-settings-layout',
            array(
                0 => array(
                    'description' 	=> 'Show images for entities',
                    'id' 			=> 'settings-entity-images',
                    'value' 		=> '0'
                ),
                1 => array(
                    'description' 	=> 'Show icons for entities',
                    'id' 			=> 'settings-entity-icons',
                    'value' 		=> '0'
                ),
                2 => array(
                    'description' 	=> 'Show only free images',
                    'id' 			=> 'settings-entity-free-images',
                    'value' 		=> '0'
                ),

            )
        );


        add_settings_field(
            'ambiverse-settings-entity-layout',
            apply_filters( $this->plugin_name . 'label-settings-entity-layout', esc_html__( 'Layout of the entity boxes', 'ambiverse-eld' ) ),
            array( $this, 'field_image_radio_button' ),
            $this->plugin_name,
            $this->plugin_name . '-settings-layout',
            array(
                0 => array(
                    'img' 	=>  plugin_dir_url( __FILE__ )  .'images/entity-layout-1.png',
                    'id' 			=> 'settings-layout-1',
                    'value' 		=> 'layout1',
                    'name'          =>  'entity-layout'
                ),
                1 => array(
                    'img' 	=> plugin_dir_url( __FILE__ )  .'images/entity-layout-2.png',
                    'id' 			=> 'settings-layout-2',
                    'value' 		=> 'layout2',
                    'name'          => 'entity-layout'
                )
            )
        );
    } // register_fields()


    /**
     * Creates a settings section
     *
     * @since 		0.9
     * @param 		array 		$params 		Array of parameters for the section
     * @return 		mixed 						The settings section
     */
    public function section_disambiguation( $params ) {

        if ( ! empty( $this->options['settings-entity-linking-endpoint'] ) ) {
            //$endpoint = $this->options['settings-api-method']; //$this->options['settings-entity-linking-endpoint'];
            //if($this->options['settings-api-method'] == "factextraction"){
            //    $endpoint = $this->options['settings-fact-extraction-endpoint'];
            //}

            $atts['entity-linking-url'] = $this->options['settings-api-protocol'] . "://" . $this->options['settings-api-endpoint'] . "." . $this->options['settings-api-url'] . $this->options['settings-api-version'] .  $this->options['settings-api-method'];
        }
        if ( ! empty( $this->options['settings-knowledge-graph-endpoint'] ) ) {
            $atts['knowledge-graph-url'] = $this->options['settings-api-protocol'] . "://" . $this->options['settings-api-endpoint'] . "." . $this->options['settings-api-url'] . $this->options['settings-api-version'] . $this->options['settings-knowledge-graph-endpoint'];
        }

        include( plugin_dir_path( __FILE__ ) . 'partials/ambiverse-eld-admin-section-disambiguation.php' );
    } // section_messages()

    /**
     * Creates a settings section
     *
     * @since 		0.9
     * @param 		array 		$params 		Array of parameters for the section
     * @return 		mixed 						The settings section
     */
    public function section_auth( $params ) {

        if ( ! empty( $this->options['settings-api-token-endpoint'] ) ) {
            $atts['api-oauth-url'] = $this->options['settings-api-protocol'] . "://" . $this->options['settings-api-endpoint'] . "." . $this->options['settings-api-url'] . $this->options['settings-api-token-endpoint'];
        }

        include( plugin_dir_path( __FILE__ ) . 'partials/ambiverse-eld-admin-section-auth.php' );
    } // section_messages()


    /**
     * Creates a settings section
     *
     * @since 		0.9
     * @param 		array 		$params 		Array of parameters for the section
     * @return 		mixed 						The settings section
     */
    public function section_layout( $params ) {


        include( plugin_dir_path( __FILE__ ) . 'partials/ambiverse-eld-admin-section-layout.php' );
    } // section_messages()

    /**
     * Creates a usage section
     *
     * @since 		0.9
     * @param 		array 		$params 		Array of parameters for the section
     * @return 		mixed 						The settings section
     */
    public function section_usage( $params ) {
        $atts['settings-api-method'] = $this->options['settings-api-method'];
        $atts['settings-api-endpoint'] = $this->options['settings-api-endpoint'];
        $atts['settings-threshold-document'] = $this->options['settings-threshold-document'];
        if( $this->options['settings-coherent-document']) {
            $atts['settings-coherent-document'] = "true";
        } else {
            $atts['settings-coherent-document'] = "false";
        }

        include( plugin_dir_path( __FILE__ ) . 'partials/ambiverse-eld-admin-usage.php' );
    } // section_messages()

    private function sanitizer( $type, $data ) {

        if ( empty( $type ) ) { return; }
        if ( empty( $data ) ) { return; }
        $return 	= '';
        $sanitizer 	= new Ambiverse_ELD_Sanitize();
        $sanitizer->set_data( $data );
        $sanitizer->set_type( $type );
        $return = $sanitizer->clean();
        unset( $sanitizer );
        return $return;
    } // sanitizer()

    /**
     * Validates saved options
     *
     * @since 		1.0.0
     * @param 		array 		$input 			array of submitted plugin options
     * @return 		array 						array of validated plugin options
     */
    public function validate_options( $input ) {
        //wp_die( print_r( $input ) );
//        $valid 		= array();
//        $options 	= $this->get_options_list();
//        foreach ( $options as $option ) {
//            $name = $option[0];
//            $type = $option[1];
//
//            $valid[$option[0]] = $this->sanitizer( $type, $input[$name] );
//
//        }


        $opts 		= array();
        $options 	= $this->get_default_options();
        //var_dump($options);
        foreach ( $options as $option ) {
            $opts[ $option[0] ] = $option[1];
        }

        $new_opts = wp_parse_args( $input, $opts);
        //var_dump($new_opts);
        return $new_opts;
    } // validate_options()

    /**
     * Returns an array of options names, fields types, and default values
     *
     * @return 		array 			An array of options
     */
    public static function get_default_options() {
        $options = array();
        $options[] = array( 'settings-api-protocol', 'https' );
        $options[] = array( 'settings-api-token-endpoint', 'oauth/token' );
        $options[] = array( 'settings-api-url', 'ambiverse.com/' );
        $options[] = array( 'settings-api-endpoint', 'api' );
        $options[] = array( 'settings-api-version', 'v1beta2' );
        $options[] = array( 'settings-entity-linking-endpoint', '/entitylinking/' );
        $options[] = array( 'settings-fact-extraction-endpoint', '/factextraction/' );
        $options[] = array( 'settings-knowledge-graph-endpoint', '/knowledgegraph/' );
        $options[] = array( 'settings-entity-layout', 'layout1' );

        return $options;
    }
}