<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use GuzzleHttp\Client;
use Firebase\JWT\JWT;

class Zoom_lib {
    private $zoom_account_id;
    private $zoom_client_id;
    private $zoom_client_secret;
    
    private $zoom_sdk_client_id;
    private $zoom_sdk_client_secret;


    private $redirect_uri;

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->model('Zoom_model');

        // Memuat konfigurasi zoom.php
        $this->CI->config->load('zoom', TRUE);
        $zoom_config = $this->CI->config->item('zoom');

        $this->zoom_account_id = $zoom_config['zoom_account_id'];
        $this->zoom_client_id = $zoom_config['zoom_client_id'];
        $this->zoom_client_secret = $zoom_config['zoom_client_secret'];

        $this->zoom_sdk_client_id = $zoom_config['zoom_sdk_client_id'];
        $this->zoom_sdk_client_secret = $zoom_config['zoom_sdk_client_secret'];

        $this->redirect_uri = $zoom_config['zoom_redirect_uri'];
    }

    /** Function : zoom_account_id, zoom_client_id, zoom_client_secret
     * Description: App credentials are the client credentials, including the account ID, client ID, and client secret, 
     * which Zoom provides to app developers to access the Zoom platform. Get your app credentials on your 
     * app details page on the Zoom App Marketplace.
     * Find out more information : https://developers.zoom.us/docs/zoom-rooms/s2s-oauth/#get-app-credentials
     */
    public function zoom_account_id()
    {
      return $this->zoom_account_id;
    }

    public function zoom_client_id()
    {
      return $this->zoom_client_id;
    }

    public function zoom_client_secret()
    {
      return $this->zoom_client_secret;
    }

    /** Function: zoom_sdk_client_id, zoom_sdk_client_secret
     * Description: To get Meeting SDK credentials, go to the Zoom App Marketplace and sign in with your 
     * Zoom user account. Select Develop and choose Build App. On the Meeting SDK app type, select Create.
     * Find out more information : https://developers.zoom.us/docs/meeting-sdk/developer-accounts/#get-meeting-sdk-credentials
     */
    public function zoom_sdk_client_id()
    {
      return $this->zoom_sdk_client_id;
    }

    public function zoom_sdk_client_secret()
    {
      return $this->zoom_sdk_client_secret;
    }

    /** 
     * Function: generate_signature
     * Description: After getting your Meeting SDK credentials, generate a JWT for authorizing each request to start or join a Zoom meeting or webinar.
     * To create a signature for the JWT, you must encrypt the header and payload with the Meeting SDK Secret or Client Secret through an HMAC SHA256 algorithm.
     * You can find the more detail about the token here: 
     * https://developers.zoom.us/docs/meeting-sdk/auth/#generate-a-meeting-sdk-jwt
     */
    public function generate_signature($meeting_id, $role, $duration)
    {

        $seconds = $duration * 60;
        
        if ($seconds < 1800) {
            $duration = 1800 / 60;
        }
        
        date_default_timezone_set('Asia/Makassar');
        $start_time = date('Y-m-d H:i:s');
        $end_time = date('Y-m-d H:i:s', strtotime('+' . $duration . ' minute'));

        $start_time = strtotime($start_time);
        $end_time = strtotime($end_time);

        $client_id = $this->zoom_sdk_client_id();
        $payload = array(
            "sdkKey" => $client_id,
            "mn" => (int)$meeting_id,
            "role" => $role,
            "iat" => $start_time,
            "exp" => $end_time,
            "tokenExp" => $end_time
        );
        $header = array(
            "alg" => "HS256",
            "typ" => "JWT"
        );

        $client_secret = $this->zoom_sdk_client_secret();
        $jwt = JWT::encode($payload, $client_secret, 'HS256', null, $header);
        return $jwt;
    }
}
