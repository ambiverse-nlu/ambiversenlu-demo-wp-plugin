<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://ambiverse.com
 * @since      0.9
 *
 * @package    Ambiverse_entitylinking_demo
 * @subpackage Ambiverse_entitylinking_demo/public
 */
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ambiverse_ELD
 * @subpackage Ambiverse_ELD/public
 * @author     Dragan Milchevski <dragan@ambiverse.com>
 */
class Ambiverse_ELD_Public {

    /**
     * The plugin options.
     *
     * @since 		0.9
     * @access 		private
     * @var 		string 			$options    The plugin options.
     */
    private $options;

    /**
     * The ID of this plugin.
     *
     * @since    0.9
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;
    /**
     * The version of this plugin.
     *
     * @since    0.9
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;
    /**
     * Initialize the class and set its properties.
     *
     * @since      0.9
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        $this->set_options();
    }
    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    0.9
     */
    public function enqueue_styles() {
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
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ambiverse-eld-public.css', array(), $this->version, 'all' );
        wp_enqueue_style( 'ladda-css', plugin_dir_url( __FILE__ ) . 'css/ladda.min.css', array(), $this->version, 'all' );
        wp_enqueue_style( 'highlightjs-railscasts-css', plugin_dir_url( __FILE__ ) . 'css/railscasts.css', array(), $this->version, 'all' );
        wp_enqueue_style( 'bootstra-slider-css', plugin_dir_url( __FILE__ ) . 'css/bootstrap-slider.css', array(), $this->version, 'all' );
    }
    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    0.9
     */
    public function enqueue_scripts() {
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
        wp_enqueue_script( 'underscore-demo', plugin_dir_url( __FILE__ ) . 'js/underscore-min.js', array(), $this->version, false );
        wp_enqueue_script( 'ladda-spin-script', plugin_dir_url( __FILE__ ) . 'js/spin.min.js', array( 'jquery' ), $this->version, false );
        wp_enqueue_script( 'ladda-script', plugin_dir_url( __FILE__ ) . 'js/ladda.min.js', array( 'jquery' ), $this->version, false );
        wp_enqueue_script( 'highlightjs-script', plugin_dir_url( __FILE__ ) . 'js/highlight.pack.js', array( 'jquery' ), $this->version, false );
        wp_enqueue_script( 'isloading-script', plugin_dir_url( __FILE__ ) . 'js/jquery.isloading.js', array( 'jquery' ), $this->version, false );
        wp_enqueue_script( 'autogrow-script', plugin_dir_url( __FILE__ ) . 'js/jquery.ns-autogrow.min.js', array( 'jquery' ), $this->version, false );
        wp_enqueue_script( 'bootstrap-slider-script', plugin_dir_url( __FILE__ ) . 'js/bootstrap-slider.js', array( 'jquery' ), $this->version, false );
        wp_enqueue_script( 'jquery-bbq', plugin_dir_url( __FILE__ ) . 'js/jquery.ba-bbq.min.js', array( 'jquery' ), $this->version, false );

        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ambiverse-eld-public.js', array( 'jquery' ), $this->version, false );

        $nonce = wp_create_nonce('ambiverse-analyze');
        wp_localize_script($this->plugin_name, 'ajax_obj', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => $nonce,
        ));
    }

    /**
     * Sends POST request to the Ambiverse API and analyzes the document
     *
     * @since 0.9
     *
     * @return json $response JSON of the text analyzis API
     */
    function analyze_document_ajax_handler() {

        check_ajax_referer('ambiverse-analyze');
        $api = new Ambiverse_API($this->options["settings-client-id"], $this->options["settings-client-secret"], $this->options["settings-api-version"], $this->options["settings-api-endpoint"]);

        if($_POST["language"] !="auto") {
            if($_POST["annotatedMentions"]!="") {
                $data = array(
                    "coherentDocument" => $_POST['coherentDocument'],
                    "confidenceThreshold" => doubleval($_POST['confidenceThreshold']),
                    "text" => str_replace('\\', '', $_POST['text']),//$_POST['text'],
                    "language" => $_POST["language"],
                    "annotatedMentions" => $_POST["annotatedMentions"],
                );
            } else {
                $data = array(
                    "coherentDocument" => $_POST['coherentDocument'],
                    "confidenceThreshold" => doubleval($_POST['confidenceThreshold']),
                    "text" => str_replace('\\', '', $_POST['text']),//$_POST['text'],
                    "language" => $_POST["language"],
                );
            }
        } else {
            if($_POST["annotatedMentions"]!="") {
                $data = array(
                    "coherentDocument" => $_POST['coherentDocument'],
                    "confidenceThreshold" => doubleval($_POST['confidenceThreshold']),
                    "text" => str_replace('\\', '', $_POST['text']),
                    "annotatedMentions" => $_POST["annotatedMentions"],
                );
            } else {
                $data = array(
                    "coherentDocument" => $_POST['coherentDocument'],
                    "confidenceThreshold" => doubleval($_POST['confidenceThreshold']),
                    "text" => str_replace('\\', '', $_POST['text']),
                );
            }
        }

        $response = $api->call_entity_linking('analyze', $data);

        echo json_encode($response);
        wp_die(); // all ajax handlers should die when finished
    }

    function entity_metatada_ajax_handler() {

        check_ajax_referer('ambiverse-analyze');

        $api = new Ambiverse_API($this->options["settings-client-id"], $this->options["settings-client-secret"], $this->options["settings-api-version"], $this->options["settings-api-endpoint"]);
        $data = $_POST['entities'];

        //echo json_encode($data);
        //wp_die();
        if($data != null && sizeof($data) > 0) {
            $response = $api->call_knowledge_graph('entities?offset=0&limit=' . sizeof($data), $data);
            echo json_encode($response);
        }
        wp_die(); // all ajax handlers should die when finished


    }


    /**
     * Render the Entity Linking Demo
     *
     * @param   array	$atts		The attributes from the shortcode
     *
     * @uses	get_option
     * @uses	get_layout
     *
     * @since 0.9
     *
     * @return	mixed	$output		Output of the buffer
     *
     */
    public function render_demo( $atts = array(), $content = null ) {
        ob_start();
        $defaults['coherent-document'] 	    =  $this->options['settings-coherent-document'];
        $defaults['confidence-threshold'] 	=  $this->options['settings-threshold-document'];
        $defaults['text']                   =  $content;
        $defaults['class']                  = "form-control";
        $defaults['rows']                   = 7;
        $defaults['cols']                   = 7;
        $defaults['max-length']             = 2000;
        $defaults['id']                     = "ambiverse-text-input";
        $defaults['name']                   = "ambiverse-text-input";
        $defaults['entity-layout']          = $this->options['entity-layout'];
        $defaults['entity-images']          = $this->options['settings-entity-images'];
        $defaults['entity-icons']           = $this->options['settings-entity-icons'];
        $defaults['entity-free-images']     = $this->options['settings-entity-free-images'];

        $languages = array();
        $supportedLanguages = array();

        if(isset($this->options["settings-language-en"])) {

            $languages[] =  array(
                "value" => "en",
                "label" => "English",
            );
            $supportedLanguages[] = "English";
        }
        if(isset($this->options["settings-language-de"])) {
            $languages[] = array(
                "value" => "de",
                "label" => "German",
            );
            $supportedLanguages[] = "German";
        }
        if(isset($this->options["settings-language-es"])) {
            $languages[] = array(
                "value" => "es",
                "label" => "Spanish",
            );

            $supportedLanguages[] = "Spanish";
        }
        if(isset($this->options["settings-language-zh"])) {
            $languages[] =  array(
                "value" => "zh",
                "label" => "Chinese",
            );

            $supportedLanguages[] = "Chinese";
        }

        $supportedLanguagesString = "";
        for($i=0; $i<sizeof($supportedLanguages); $i++) {
            if($i>0 && $i<sizeof($supportedLanguages)-1) {
                $supportedLanguagesString.=", ";
            }
            if($i == sizeof($supportedLanguages)-1){
                $supportedLanguagesString.=", or ";
            }
            $supportedLanguagesString.=$supportedLanguages[$i];
        }

        $supportedLanguagesString.=".";

        $defaults['languages'] = $languages;
        $defaults['supported-languages'] = $supportedLanguagesString;

        $args = shortcode_atts( $defaults, $atts, 'ambiverse-eld' );



        include ambiverse_eld_get_template( 'ambiverse-eld-public-display' );


        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    } // list_openings()

    /**
     * Registers all shortcodes at once
     *
     * @return [type] [description]
     */
    public function register_shortcodes() {
        add_shortcode( 'ambiverse-eld', array( $this, 'render_demo' ) );
    }

    /**
     * Sets the class variable $options
     *
     * @since 0.9
     */
    private function set_options() {
        $this->options = get_option( $this->plugin_name . '-options' );
    }

}