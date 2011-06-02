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
	 * Configuration
	 * @var Zend_Config
	 */
	private $config = null;
	
	/**
	 * Response object
	 * 
	 * @var Html5Wiki_Routing_Response
	 */
	private $response = null;

	/**
	 * Request object
	 * 
	 * @var Html5Wiki_Routing_Interface_Request
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
	 * 
	 * @param Zend_Config                         $config   Configuration
	 * @param Html5Wiki_Routing_Response          $reqponse Response object
	 * @param Html5Wiki_Routing_Interface_Request $request  Request object [optional]
	 */
	public function __construct(Zend_Config $config, Html5Wiki_Routing_Response $response, 
								Html5Wiki_Routing_Interface_Request $request = null) {
		$this->config = $config;
		$this->response = $response;
		
		if (!$request) {
			$this->request = new Html5Wiki_Routing_Request();
		} else {
			$this->request = $request;
		}
	}

	/**
	 * Routes request according to informations from the request object
	 */
	public function route() {
		$this->request->parse();
		
		$arguments = $this->request->getArguments();

		$this->controller = isset($arguments[1]) ? $arguments[1] : $this->config->routing->defaultController;
		$this->action = isset($arguments[2]) ? $arguments[2] : $this->config->routing->defaultAction;
		
		$this->sanitizeControllerAndAction();
	}

	/**
	 * Sanitize controller and action strings<br/>
	 * <br/>
	 * The controller does not allow any special characters.<br/>
	 * The action allows dashes in addition. This is only necessary since
	 * article-permalinks can have dashes inside. "Real" actions will never
	 * have a dash inside.
	 *
	 * @throws Html5Wiki_Exception
	 */
	private function sanitizeControllerAndAction() {
		$controllerPattern = '/^[a-z]+[0-9]*[a-z]*$/i';
		$actionPattern = '/^[a-z-]+[0-9-]*[a-z-]*$/i';
		
		if (!preg_match($controllerPattern, $this->controller)) {
			throw new Html5Wiki_Exception('Invalid controller specified');
		}
		if (!empty($this->action) && !preg_match($actionPattern, $this->action)) {
			throw new Html5Wiki_Exception('Invalid action specified');
		}
	}
	
	/**
	 * Redirect to the specified url with the specified status code.
	 * 
	 * @param string $url            URL to redirect to
	 * @param int    $httpStatusCode Status code for redirecting
	 */
	public function redirect($url, $httpStatusCode = 302) {
		$this->response->pushHeader("Location: " . $url, true, $httpStatusCode);
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
	
	/**
	 * Pass as many url parts as you want (without any slashes or anything!) and
	 * this method will create you a valid URL with basepath and everything.
	 *
	 * @param 0-n url parts
	 * @return valid URL with basepath plus all url parts from the parameterlist.
	 */
	public function buildUrl(array $parts) {
		$basePath = $this->getRequest()->getBasePath();
		$target = implode('/', $parts);
		$url = $basePath. '/'. $target;
		
		return $url;
	}

}

?>
