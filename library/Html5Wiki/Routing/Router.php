<?php
/**
 * This file is part of the HTML5Wiki Project.
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright Html5Wiki 2011
 * @package Html5Wiki
 * @subpackage Library
 */

/**
 * Description of Router
 *
 * @author michael
 */
class Html5Wiki_Routing_Router implements Html5Wiki_Routing_Interface_Router {
	/**
	 * Request object
	 * 
	 * @var Html5Wiki_Routing_Interface_Router
	 */
	private $request = null;

	/**
	 * Construct router -> creates a new request object and calls parse on it
	 */
    public function __construct() {
		$this->request = new Html5Wiki_Routing_Request();
		$this->request->parse();
	}

	/**
	 * Routes request according to informations from the request object
	 */
	public function route() {
		
	}

	/**
	 * Get request object
	 * 
	 * @return Request
	 */
	public function getRequest() {
		return $this->request;
	}
}
?>
