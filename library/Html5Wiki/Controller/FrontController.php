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
 * Description of FrontController
 *
 * @author michael
 */
class Html5Wiki_Controller_FrontController {

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
	 * Creates a new router
	 */
	public function __construct($basePath, $libraryPath, $applicationPath) {
		$this->router = new Html5Wiki_Routing_Router();
		$this->router->route();

		$this->basePath = $basePath;
		$this->libraryPath = $libraryPath;
		$this->applicationPath = $applicationPath;
	}

	/**
	 * Dispatches the request
	 */
	public function dispatch() {
		$this->controller = Html5Wiki_Controller_ControllerFactory::factory($this->applicationPath, $this->router);
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
