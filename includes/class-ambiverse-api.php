<?php

/**
 * Ambiverse API class
 *
 * @link       https://ambiverse.com
 * @since      0.9
 *
 * @package    Ambiverse_ELD
 * @subpackage Ambiverse_ELD/lib
 */
/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    Ambiverse_ELD
 * @subpackage Ambiverse_ELD/includes
 * @author     Dragan Milchevski <dragan@ambiverse.com>
 */
class Ambiverse_API
{

    /**
     * @var string Protocol for accessing the api
     */
    protected $protocol = 'https';

    /**
     * @var string The URL to the Ambiverse OAuth API Endpoint
     */
    protected $api_token_endpoint = 'oauth/token';


    /**
     * @var string The URL to the Ambiverse API
     */
    protected $api_url = 'ambiverse.com/';

    /**
     * @var string API Endpoint
     */
    protected $api_endpoint = 'api';

    /**
     * @var string The Version to the Ambiverse API
     */
    protected $api_version = 'v1beta2';

    /**
     * @var string The Entity Linking Ednpoint to the Ambiverse API
     */
    protected $entity_linking_endpoint = '/entitylinking/';

    /**
     * @var string The Knowledge Graph Ednpoint to the Ambiverse API
     */
    protected $knowledge_graph_endpoint = '/knowledgegraph/';


    /**
     * @var string The Client ID to the Ambiverse API
     */
    protected $client_id = '';

    /**
     * @var string The Client Secret to the Ambiverse API
     */
    protected $client_secret = '';


    /**
     * @var string The error message of the latest API request (if any)
     */
    protected $error_message = '';

    /**
     * @var int The error code of the last API request (if any)
     */
    protected $error_code = 0;

    /**
     * @var array The Header of the object
     */
    protected $headers = array(
        'Accept' => 'application/json',
        );

    /**
     * Constructor
     *
     * @param string $client_id
     * @param string $client_secret
     * @param string $api_version
     */
    public function __construct( $client_id,  $client_secret, $api_version, $api_endpoint) {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->api_version = $api_version;
        $this->api_endpoint = $api_endpoint;

        $this->api_token_endpoint = $this->protocol . "://" . $this->api_endpoint . "." . $this->api_url . $this->api_token_endpoint;
        $this->knowledge_graph_endpoint = $this->protocol . "://" . $this->api_endpoint . "." . $this->api_url . $this->api_version . $this->knowledge_graph_endpoint;
        $this->entity_linking_endpoint =$this->protocol . "://" . $this->api_endpoint . "." . $this->api_url . $this->api_version . $this->entity_linking_endpoint;


    }


    public function get_access_token() {

        // this code runs when there is no valid transient set
        if ( ''  === ( $value = get_transient( 'ambiverse_eld_access_token' ) ) || false  === ( $value = get_transient( 'ambiverse_eld_access_token' ) )) {

            // Construct the body for the access token request
            $authentication_request_body =
                array(
                    'grant_type' => 'client_credentials',
                    'client_id' => $this->client_id,
                    'client_secret' => $this->client_secret
                );

            $response = $this->call($this->api_token_endpoint, '', $authentication_request_body, true);
            set_transient( 'ambiverse_eld_access_token', $response->access_token, intval($response->expires_in) );
        }
        return get_transient( 'ambiverse_eld_access_token' );
    }


    public function call_entity_linking($method, array $data = array()) {

        return $this->call($this->entity_linking_endpoint, $method, $data);
    }

    public function call_knowledge_graph($method, array $data = array()) {
        return $this->call($this->knowledge_graph_endpoint, $method, $data);
    }



    /**
     * Calls the Ambiverse NLU API
     *
     * @uses WP_HTTP
     *
     *
     * @param string $endpoint
     * @param array $data
     *
     * @return object
     */
    public function call( $endpoint, $method = '', array $data = array(), $auth = false ) {

        if(!$auth) {
            $access_token = $this->get_access_token();
            $this->add_to_header('Authorization', 'Bearer: '.$access_token);
            $this->add_to_header('Content-Type', 'application/json;');
            $data = json_encode($data);
        } else {
            $this->add_to_header('Content-Type', 'application/x-www-form-urlencoded');
            $data = http_build_query($data);
        }

        $request_args = array(
            'body' => $data,
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => $this->get_headers(),
        );

        //echo json_encode($request_args);
        //wp_die();


        $url = $endpoint . $method;
        //$url = "http://localhost:8080/aida/analyze";
        $response = wp_remote_post( esc_url_raw($url), $request_args );

        try {
            $response = $this->parse_response( $response );

        } catch( Exception $e ) {
            $this->error_code = $e->getCode();
            $this->error_message = $e->getMessage();
            $this->show_connection_error( $e->getMessage() );

            return false;
        }

        // store error (if any)
        if( is_object( $response ) ) {
            if( ! empty( $response->error ) ) {
                $this->error_message = $response->error;
            }

            // store error code (if any)
            if( ! empty( $response->code ) ) {
                $this->error_code = (int) $response->code;
            }
        }

        return $response;
    }

    /**
     * @param array|WP_Error $response
     * @return object
     * @throws Exception
     */
    private function parse_response( $response ) {

        if( is_wp_error( $response ) ) {
            throw new Exception( 'Error connecting to Ambiverse NLU API. ' . $response->get_error_message(), (int) $response->get_error_code() );
        }


        $code = (int) wp_remote_retrieve_response_code( $response );
        $message = wp_remote_retrieve_response_message( $response );

        if( $code !== 200 ) {
            $body = json_decode(wp_remote_retrieve_body( $response ));
            $error = array(
                "code" => $code,
                "message" =>$body->message,
            );
            return $error;
        }

        // decode response body
        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body);//, false, 512, JSON_UNESCAPED_UNICODE );
        if( ! is_null( $data ) ) {
            return $data;
        }



        throw new Exception( $message, $code );
    }

    /**
     * @param $message
     *
     * @return bool
     */
    private function show_connection_error( $message ) {
        $message .= '<br /><br />';
        return $message;
    }



    /**
     * Get the request headers to send to the Ambiverse API
     *
     * @return array
     */
    private function get_headers() {

        return $this->headers;
    }

    private function add_to_header($key, $value){
         $this->headers[$key] = $value;
    }

    public function __toString() {
        return "client_id=".$this->client_id .
            "\nclient_secret=" . $this->client_secret .
            "\n\nheaders=" . json_encode($this->headers);
    }
}