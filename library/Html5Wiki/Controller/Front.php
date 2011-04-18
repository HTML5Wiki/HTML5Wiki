<?php
/**
 * The FrontController sets the whole system up and dispatches the request
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @package Library
 * @subpackage Controller
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
	 * Controller
	 * @var Html5Wiki_Controller_AbstractController
	 */
	private $controller = null;

	/**
	 * Base path of the whole application
	 * @var string
	 */
	private $basePath = '';

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
	 * Setup front controller
	 *
	 * @param Zend_Config $config Application configuration
	 * @param string $basePath        Base path of the wiki
	 * @param string $libraryPath     Library path
	 * @param string $applicationPath Application path
	 * @param Html5Wiki_Routing_Router_Interface $router Router, optional. If null given, a new
	 *                                                   Html5Wiki_Routing_Router will be instantiated
	 */
	public function __construct(Zend_Config $config, $basePath, $libraryPath, $applicationPath, $router = null) {
		$this->config = $config;

		if (!is_string($basePath) || !is_string($libraryPath) || !is_string($applicationPath)) {
			throw new Html5Wiki_Exception_InvalidArgument("All paths given to " . __CLASS__ . " should be strings.");
		}

		if (!$router) {
			$this->router = new Html5Wiki_Routing_Router();
		} else {
			$this->router = $router;
		}
		$this->router->route();

		$this->basePath = $basePath;
		$this->libraryPath = $libraryPath;
		$this->applicationPath = $applicationPath;
	}

	/**
	 * Dispatches the request
	 */
	public function dispatch() {
		$this->controller = Html5Wiki_Controller_Factory::factory($this->applicationPath, $this->router);
		$this->controller->dispatch($this->router);
		$this->controller->render();
	}

	/**
	 * Gets router object
	 * @return Html5Wiki_Routing_Router
	 */
	public function getRouter() {
		return $this->router;
	}

}

?>
