<?php
/**
 * Router interface
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @package Library
 * @package Routing
 */
interface Html5Wiki_Routing_Interface_Router {
	/**
	 * Construct router -> creates a new request object and calls parse on it
	 * 
	 * @param Zend_Config                         $config  Configuration
	 * @param Html5Wiki_Routing_Interface_Request $request Request object [optional]
	 */
    public function __construct(Zend_Config $config, Html5Wiki_Routing_Interface_Request $request = null);
	
	/**
	 * Routes request according to informations from the request object
	 */
	public function route();
	
	/**
	 * Redirect to the specified url with the specified status code.
	 * 
	 * @param string $url            URL to redirect to
	 * @param int    $httpStatusCode Status code for redirecting
	 */
	public function redirect($url, $httpStatusCode = 302);
	
	/**
	 * Get request object
	 * 
	 * @return Html5Wiki_Routing_Interface_Request
	 */
	public function getRequest();
	
	/**
	 * Set request object
	 * @param Html5Wiki_Routing_Interface_Request $request
	 */
	public function setRequest(Html5Wiki_Routing_Interface_Request $request);

	/**
	 * Get controller
	 * @return string
	 */
	public function getController();
	
	/**
	 * Get action
	 * @return string
	 */
	public function getAction();
}

?>
