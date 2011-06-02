<?php
/**
 * This file is part of the HTML5Wiki Project.
 * 
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * http://www.github.com/HTML5Wiki/HTML5Wiki/blob/master/LICENSE
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to mweibel@hsr.ch so we can send you a copy immediately.
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @category Html5Wiki
 * @package Library
 * @subpackage Controller
 */

/**
 * The FrontController sets the whole system up and dispatches the request
 */
class Html5Wiki_Controller_Front {

	/**
	 * Configuration
	 * @var Zend_Config
	 */
	private $config = null;

	/**
	 * Router object
	 * @var Html5Wiki_Routing_Router
	 */
	private $router = null;
	
	/**
	 * Response object
	 * @var Html5Wiki_Routing_Response 
	 */
	private $response = null;

	/**
	 * Controller
	 * @var Html5Wiki_Controller_AbstractController
	 */
	private $controller = null;

	/**
	 * Base path of the whole application
	 * @var string
	 */
	private $systemBasePath = '';

	/**
	 * Path to the library
	 * @var string
	 */
	private $libraryPath = '';

	/**
	 * Application path
	 * @var string
	 */
	private $applicationPath = '';
	
	/**
	 * Front controller instance.
	 * 
	 * @var Html5Wiki_Controller_Front
	 */
	private static $instance = null;

	/**
	 * Setup front controller
	 *
	 * @param Zend_Config $config Application configuration
	 * @param string $systemBasePath        Base path of the wiki
	 * @param string $libraryPath     Library path
	 * @param string $applicationPath Application path
	 * @param Html5Wiki_Routing_Router_Interface $router Router, optional. If null given, a new
	 *                                                   Html5Wiki_Routing_Router will be instantiated
	 */
	public function __construct(Zend_Config $config, $systemBasePath, $libraryPath, $applicationPath, $router = null, $response = null) {
		$this->config = $config;

		if (!is_string($systemBasePath) || !is_string($libraryPath) || !is_string($applicationPath)) {
			throw new Html5Wiki_Exception_InvalidArgument("All paths given to " . __CLASS__ . " should be strings.");
		}
		
		if (!$response) {
			$this->response = new Html5Wiki_Routing_Response();
		} else {
			$this->response = $response;
		}

		if (!$router) {
			$this->router = new Html5Wiki_Routing_Router($this->config, $this->response);
		} else {
			$this->router = $router;
		}

		$this->systemBasePath = $systemBasePath;
		$this->libraryPath = $libraryPath;
		$this->applicationPath = $applicationPath;
		
		self::setInstance($this);
		
		$this->router->route();
	}
	
	/**
	 * Set static instance
	 * @param Html5Wiki_Controller_Front $instance 
	 */
	public static function setInstance(Html5Wiki_Controller_Front $instance) {
		self::$instance = $instance;
	}
	
	/**
	 * Get front controller instance
	 * @return Html5Wiki_Controller_Front
	 */
	public static function getInstance() {
		return self::$instance;
	}

	/**
	 * Runs the request
	 */
	public function run() {
		$this->controller = $this->createController();
		
		$this->controller->setConfig($this->config);
		$this->controller->setSystemBasePath($this->systemBasePath);
		
		$this->dispatch();
	}

	/**
	 * Dispatch request to controller
	 */
	public function dispatch() {
		$this->controller->dispatch($this->router, $this->response);
	}

	/**
	 * Call rendering method of controller
	 */
	public function render() {
		$this->controller->render();
		return $this->response->render();
	}
	
	/**
	 *
	 * @return Zend_Config
	 */
	public function getConfig() {
		return $this->config;
	}

	/**
	 * Get the controller from the factory
	 * @return Html5Wiki_Controller_Abstract
	 */
	protected function createController() {
		return Html5Wiki_Controller_Factory::factory($this->applicationPath, $this->router, $this->response);
	}

	/**
	 * Gets router object
	 * @return Html5Wiki_Routing_Router
	 */
	public function getRouter() {
		return $this->router;
	}

	/**
	 * Get response object
	 * @return Html5Wiki_Routing_Response
	 */
	public function getResponse() {
		return $this->response;
	}
	
	/**
	 * If the development-property in the config is set to true, this method
	 * returns false to indicate, that the current environment is not a
	 * productive one.
	 *
	 * @return true/false
	 */
	public function isProductive() {
		$productive = true;
		
		if(isset($this->config->development)) {
			$productive = !$this->config->development;
		}
		
		return $productive;
	}
}

?>
