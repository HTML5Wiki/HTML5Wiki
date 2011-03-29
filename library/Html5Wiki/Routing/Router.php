<?php

/**
 * The router figures out the controller and action for the current page.
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @package Library
 * @subpackage Routing
 */
class Html5Wiki_Routing_Router implements Html5Wiki_Routing_Interface_Router {
	/**
	 * Default controller if none is supplied
	 */
	const DEFAULT_CONTROLLER = 'index';

	/**
	 * Default action if none is supplied
	 */
	const DEFAULT_ACTION = 'index';

	/**
	 * Request object
	 * 
	 * @var Html5Wiki_Routing_Interface_Router
	 */
	private $request = null;
	
	/**
	 * Controller
	 *
	 * @var string
	 */
	private $controller = null;

	/**
	 * Action
	 * @var string
	 */
	private $action = null;

	/**
	 * Construct router -> creates a new request object and calls parse on it
	 */
	public function __construct() {
		$this->request = new Html5Wiki_Routing_Request();
	}

	/**
	 * Routes request according to informations from the request object
	 */
	public function route() {
		$this->request->parse();

		$arguments = $this->request->getArguments();

		$this->controller = isset($arguments[1]) ? $arguments[1] : self::DEFAULT_CONTROLLER;
		$this->action = isset($arguments[2]) ? $arguments[2] : self::DEFAULT_ACTION;

		$this->sanitizeControllerAndAction();
	}

	private function sanitizeControllerAndAction() {
		$pattern = '/^[a-z]+[0-9]*[a-z]*$/i';
		if (!preg_match($pattern, $this->controller)) {
			throw new Html5Wiki_Exception('Invalid controller specified');
		}
		if (!empty($this->action) && !preg_match($pattern, $this->action)) {
			throw new Html5Wiki_Exception('Invalid action specified');
		}
	}

	/**
	 * Get request object
	 * 
	 * @return Html5Wiki_Routing_Interface_Request
	 */
	public function getRequest() {
		return $this->request;
	}

	/**
	 * Set request object
	 * @param Html5Wiki_Routing_Interface_Request $request
	 */
	public function setRequest(Html5Wiki_Routing_Interface_Request $request) {
		$this->request = $request;
	}

	/**
	 * Get controller
	 * @return string
	 */
	public function getController() {
		return $this->controller;
	}

	/**
	 * Get action
	 * @return string
	 */
	public function getAction() {
		return $this->action;
	}

}

?>
