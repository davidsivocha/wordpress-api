<?php
/**
 * Plugin Name: Wordpress API Endpoints
 * Description: Adds an API endpoint at /api/
 * Version: 0.1
 * Author: David Sivocha
 * Author URL: http://sivocha.com
 */

require_once('classes/ApiEndpoint.php');
new DavidSivocha\WordpressApi\ApiEndpoint();
