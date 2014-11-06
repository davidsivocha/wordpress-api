<?php
/**
 * Plugin Name: Wordpress API Endpoints
 * Description: Adds an API endpoint at /api/
 * Version: 0.1
 * Author: David Sivocha
 * Author URL: http://sivocha.com
 */
 
class API_Endpoint {

	/** 
	 *  Hook WordPress
	 *	@return void
	 */
	public function __construct(){
		add_filter('query_vars', array($this, 'add_query_vars'), 0);
		add_action('parse_request', array($this, 'sniff_requests'), 0);
		add_action('init', array($this, 'add_endpoint'), 0);
	}	
	
	/** 
	 *  Add public query vars
	 *  Use this function to add the variables you want to be using for your API
	 *	@param array $vars List of current public query vars
	 *	@return array $vars 
	 */
	public function add_query_vars($vars){
		$vars[] = '__api';
		$vars[] = 'example';
		return $vars;
	}
	
	/** 
	 *  Add API Endpoint
	 *	This is where the magic happens - brush up on your regex skillz
	 *	Remember that the Rewrite Rules will execute in the Order listed, so the Most complex ones need to go at the top
	 *	@return void
	 */
	public function add_endpoint(){
		add_rewrite_rule('^api/?([0-9]+)?/?','index.php?__api=1&example=$matches[1]','top');
	}
 
	/**	
	 *  Sniff Requests
	 *	This is where we hijack all API requests
	 * 	If $_GET['__api'] is set, we kill WP and serve up our API
	 *	@return die if API request
	 */
	public function sniff_requests(){
		global $wp;
		if(isset($wp->query_vars['__api'])){
			$this->handle_request();
			exit;
		}
	}
	
	/** 
	 *  Handle Requests
	 *	This is where we deal with our variables and process them as needed!
	 *	@return void 
	 */
	protected function handle_request(){
		global $wp;
		$example = $wp->query_vars['example'];
		if(!$example)
			$this->send_response('Invalid API Request');
		
		if($example)
			$this->send_response('200 OK', $example);
	}
	
	/** 
	 *  Response Handler
	 *	This sends a JSON response to the browser
	 *	@param string msg A message to be shown in the response
	 *	@param array/string data the Data that will be sent, should either be an array or a string
	 *	@param int error Boolean field to indicate an error, defaults to false
	 *	@param array/String errorMsg A string or array containing the error messages
	 */
	protected function send_response($msg, $data = '', $error = 0, $errorMsg = ''){
		$response['message'] = $msg;
		if($data)
			$response['data'] = $data;
		$response['error'] = $error;
		$response['error_messages'] = $errorMsg;
		header('content-type: application/json; charset=utf-8');
	    echo json_encode($response)."\n";
	    exit;
	}
}

new API_Endpoint();