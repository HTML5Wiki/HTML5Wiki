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

		$actionMethod = $this->router->getAction() . 'Action';

		if (method_exists($this, $actionMethod)) {
			return $this->$actionMethod();
		}
		throw new Html5Wiki_Exception_404Exception('Invalid action "' . $actionMethod . '" in class "' . get_class($this) .'"');
	}

	abstract public function render();
}
?>
