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
	 * default layout file
	 */
	const DEFAULT_LAYOUT_FILE = 'layout.php';

	/**
	 * Router
	 * @var Html5Wiki_Routing_Interface_Router
	 */
	private $router = null;

	/**
	 * Template object
	 * @var Html5Wiki_Template_Decorator
	 */
	protected $template = null;

	/**
	 * Template file
	 * @var string
	 */
	private $templateFile = '';

	/**
	 * Layout file
	 * @var string
	 */
	private $layoutFile = '';

	public function __construct() {
		$this->template = new Html5Wiki_Template_Php();
		$this->layoutFile = self::DEFAULT_LAYOUT_FILE;
	}

    public function dispatch(Html5Wiki_Routing_Interface_Router $router) {
		$this->router = $router;

		$this->templateFile = strtolower($this->router->getController()) . '/' . strtolower($this->router->getAction()) . '.php';

		$actionMethod = $this->router->getAction() . 'Action';

		if (method_exists($this, $actionMethod)) {
			return $this->$actionMethod();
		}
		throw new Html5Wiki_Exception_404Exception('Invalid action "' . $actionMethod . '" in class "' . get_class($this) .'"');
	}

	public function render() {
		$this->template->render($this->templateFile);
	}
}
?>
