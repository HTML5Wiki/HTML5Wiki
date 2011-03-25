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
 * Description of AbstractController
 *
 * @author michael
 */
abstract class Html5Wiki_Controller_AbstractController {

	/**
	 * Router
	 * @var Html5Wiki_Routing_Interface_Router
	 */
	private $router = null;

	public function __construct() {
		
	}

    public function dispatch(Html5Wiki_Routing_Interface_Router $router) {
		$this->router = $router;

		$actionMethod = $this->router->getRequest()->getAction() . 'Action';

		return $this->$actionMethod();
	}
}
?>
