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
	 * Creates a new router
	 */
	public function __construct() {
		$this->router = new Html5Wiki_Routing_Router();
		$this->router->route();

		$this->basePath = realpath(dirname(__FILE__) . '../../../');
	}

	/**
	 * Dispatches the request
	 */
	public function dispatch() {
		$controller = Html5Wiki_Controller_ControllerFactory::factory($this->basePath, $this->router);
		$controller->dispatch($this->router);
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
