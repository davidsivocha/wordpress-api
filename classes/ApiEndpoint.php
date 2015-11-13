<?php
namespace DavidSivocha\WordpressApi;

class ApiEndpoint
{

    /**
     *  Hook WordPress
     *  @return void
     */
    public function __construct()
    {
        add_filter('query_vars', array($this, 'addQueryVars'), 0);
        add_action('parse_request', array($this, 'sniffRequests'), 0);
        add_action('init', array($this, 'addEndpoint'), 0);
    }

    /**
     *  Add public query vars
     *  Use this function to add the variables you want to be using for your API
     *  @param array $vars List of current public query vars
     *  @return array $vars
     */
    public function addQueryVars($vars)
    {
        $vars[] = '__api';
        $vars[] = 'example';
        return $vars;
    }

    /**
     *  Add API Endpoint
     *  This is where the magic happens - brush up on your regex skillz
     *  Remember that the Rewrite Rules will execute in the Order listed, so the Most complex ones need to go at the top
     *  @return void
     */
    public function addEndpoint()
    {
        add_rewrite_rule('^api/?([0-9]+)?/?','index.php?__api=1&example=$matches[1]','top');
    }

    /**
     *  Sniff Requests
     *  This is where we hijack all API requests
     *  If $_GET['__api'] is set, we kill WP and serve up our API
     *  @return die if API request
     */
    public function sniffRequests()
    {
        global $wp;
        if(isset($wp->query_vars['__api'])){
            $this->handleRequest();
            exit;
        }
    }

    /**
     *  Handle Requests
     *  This is where we deal with our variables and process them as needed!
     *  @return void
     */
    protected function handleRequest()
    {
        global $wp;
        $example = $wp->query_vars['example'];

        if(!$example) {
            $this->sendResponse('Invalid API Request', '', true, 'Invalid Api Request', 500);
        }

        if($example) {
            $this->sendResponse('200 OK', $example);
        }
    }

    /**
     *  Response Handler
     *  This sends a JSON response to the browser
     *  @param string msg A message to be shown in the response
     *  @param array/string data the Data that will be sent, should either be an array or a string
     *  @param boolean error Boolean field to indicate an error, defaults to false
     *  @param array/String errorMsg A string or array containing the error messages
     *  @param int The Error Code you want to send to the browser on Fail
     */
    protected function send_response($msg, $data = '', $error = false, $errorMsg = '', $errorCode = 500)
    {
        $response['message'] = $msg;

        if($data) {
            $response['data'] = $data;
        }

        $response['error'] = $error;
        $response['error_messages'] = $errorMsg;
        if($response['error']) {
            http_response_code($errorCode);
        }
        header('content-type: application/json; charset=utf-8');
        echo json_encode($response)."\n";
        exit;
    }
}
