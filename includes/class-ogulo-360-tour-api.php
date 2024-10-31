<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.ogulo.de/
 * @since      1.0.0
 *
 * @package    Ogulo_360_Tour
 * @subpackage Ogulo_360_Tour/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ogulo_360_Tour
 * @subpackage Ogulo_360_Tour/API
 * @author     Rextheme <support@rextheme.com>
 */
class Ogulo_360_Tour_Admin_API
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    private $url;

    private $api_key;

    private $headers;

    private $body;

    private $provider_id;

    private $method;

    private $type;

    private $token;

    private $all_tours;
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($body = [], $headers = [])
    {

        /*$this->plugin_name = $plugin_name;
        $this->version = $version;*/
        $this->type = 'json';
        $this->domain = 'https://api.ogulo.com';
        $this->tour_domain = 'https://tour.ogulo.com';
        $this->url = '';
        
        $this->headers = (array) $headers;
        $this->body = (array) $body;
        $this->provider_id = '56f29d99-36ae-46a8-bb8a-69e131d716f2';
        

        $this->token = false;
        $this->all_tours = [];
        $this->single_tour = [];
    }


    /**
     * Get Token.
     *
     * @since    1.0.0
     */

    public function get_token()
    {

        $transient = 'oogulo_token_expires_at';
        $verification_key = 'oogulo_verification_key';
        $exists_token = get_transient($transient);
        $exists_key = get_transient($verification_key);

        if (!empty($exists_token) && !empty($exists_key)) {
            return $this->token =  $exists_token;
        }
        return false;
    }

    public function set_token()
    {

        $transient = 'oogulo_token_expires_at';
        $verification_key = 'oogulo_verification_key';


        $this->method = 'POST';
        $this->url = $this->domain . '/user/login';

        if ($this->type == 'json' && is_array($this->body)) {
            $api_key = $this->body['api_key'];
            $this->body['provider_id'] = $this->provider_id;
            $this->body = json_encode($this->body);
        }

        $response = $this->send();

        //file_put_contents(ABSPATH.'/ogulo/API_set_token_info_'.rand(2,20000).'.txt', json_encode( $response ));
        if ($response['success']) {
            $rbody = $body = $response['success']['body'];
            $code = $response['success']['response']['code'];

            if (!empty($rbody) && $code == 200) {
                $body = json_decode($rbody);
                $this->token = $body->response->access_token;
                set_transient($transient, $this->token, 13 * 60);
                //set_transient( $verification_key, $api_key );
            }/*else{

            }*/
        }

        return $this->token;
    }


    /**
     * getCompany
     *
     * get and return the neccesary information about the owning company
     *
     * @since		1.0.0
     * @param  		string $id : the companyID
     * @return 		mixed
     */
    public function getCompany($id = null)
    {

        if (!empty($id)) {
            $this->method = 'GET';
            $this->url = $this->domain . '/company/' . $id;
            $this->headers['access-token'] = $this->token;
            $original_body = $this->body;
            $this->body = ''; //set body for this service

            $response = $this->send();

            $this->body = $original_body; // set original body
            //file_put_contents(ABSPATH.'/ogulo/API_tour_info.txt', json_encode( $response ));

            $code = $response['success']['response']['code'];
            if ($code == 200) {
                $body = json_decode($response['success']['body']);
                return $body->response;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }



    /**
     * Get all tours
     *
     * @since    1.0.0
     */

    //https://api.ogulo.com/tour/all?shortcode=8ATP
    public function get_tour($shortcode = '')
    {

        if (!empty($this->single_tour)) { //cached as object
            return $this->single_tour;
        }
        $this->method = 'GET';
        $this->url = $this->domain . '/tour/all?short_code=' . $shortcode;
        $this->headers['access-token'] = $this->token;
        $original_body = $this->body;
        $this->body = ''; //set body for this service

        $response = $this->send();

        $this->body = $original_body; // set original body
        //file_put_contents(ABSPATH.'/ogulo/API_tour_info_'.$shortcode.'.txt', json_encode( $response ));
        if ($response['success']) {
            $code = $response['success']['response']['code'];
            if ($code == 200) {
                $body = json_decode($response['success']['body']);
                $this->single_tour = $body->response;
            }
        }
        return $this->single_tour;
    }

    /**
     * Get all tours
     *
     * @return array : array of tours
     */
    public function getTours(): array
    {
        // get the total amount of tours
        $totalAmountOfTours = (int)$this->getTotalAmountOfTours();
        if (!empty($this->all_tours)) { //cached as object
            if ($totalAmountOfTours === count($this->all_tours)) {
                return $this->all_tours;
            }
        }
        
        // get the tours in chunks
        $chunksize = 25;
        if ($totalAmountOfTours >= $chunksize) {
            for ($i = 0; $i <= $totalAmountOfTours; $i = $i + $chunksize) {
                $tourResponse = $this->getTourChunks($chunksize, $i);
                array_push($this->all_tours, ...$tourResponse);
            }
        } else {
            $tourResponse = $this->getTourChunks($chunksize, 0);
            array_push($this->all_tours, ...$tourResponse);
        }
        


        return $this->all_tours;
    }

    /**
     *
     */
    private function send()
    {
        $return = [];

        $response = wp_remote_post(
            $this->url,
            array(
                'method'      => $this->method,
                'timeout'     => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking'    => true,
                'headers'     => $this->headers,
                'body'        => $this->body,
                'cookies'     => array()
            )
        );

        if (is_wp_error($response)) {
            $return['error'] = $response->get_error_message();
        } else {
            $return['success'] = $response;
        }
        return $return;
    } //send

    /**
     * Get the tours by chunks
     * 
     * @param $limit  int : the maximum amount of tours that shall be returned
     * @param $offset int : the offset the returned list is starting at
     * 
     * @return array : an array of tours or even an empty array
     */
    public function getTourChunks($limit=50, $offset=0): array 
    {
        $urlParams = [
            'limit' => $limit,
            'offset' => $offset,
            'status_id' => '100,0,400,200,301'
        ];
        $this->method = 'GET';
        $this->url = $this->addParamsToUrl($this->domain . '/tour/all', $urlParams);
        $this->headers['access-token'] = $this->token;
        $original_body = $this->body;
        $this->body = ''; //set body for this service

        $response = $this->send();

        $this->body = $original_body; // set original body
        //file_put_contents(ABSPATH.'/ogulo/API_tour_info_'.$shortcode.'.txt', json_encode( $response ));
        if ($response['success']) {
            $code = $response['success']['response']['code'];
            if ($code == 200) {
                $body = json_decode($response['success']['body']);
                return $body->response;
            }
        }
        return [];
    }

    /**
     * Get the total Amount of tours for this account
     * 
     * @return int
     */
    public function getTotalAmountOfTours() 
    {
        $urlParams = [
            'limit' => 0,
            'offset' => 0,
            'status_id' => '100,0,400,200,301'
        ];
        $this->method = 'GET';
        $this->url = $this->addParamsToUrl($this->domain . '/tour/all', $urlParams);
        $this->headers['access-token'] = $this->token;
        $original_body = $this->body;
        $this->body = ''; //set body for this service

        $response = $this->send();

        
        if ($response['success']) {
            $code = $response['success']['response']['code'];
            if ($code == 200) {
                $body = json_decode($response['success']['body']);
                return $body->metadata->total_results;
            }
        }
        return 0;
    }

    /**
     * Append Parameter to URL
     * 
     * @param $url    string : url to append params to
     * @param $params array  : params to append as associative array
     * 
     * @return string : the completed url
     */
    public function addParamsToUrl($url, $params = []) 
    {
        if (empty($params)) {
            return $url;
        }
    
        $firstElement = true;
        foreach ($params as $key => $value) {
            $url .= ($firstElement?'?':'&') . $key . '=' . $value;
            $firstElement=false;  
        }
        return $url;
    }

}
