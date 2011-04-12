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
abstract class Html5Wiki_Controller_Abstract {

	/**
	 * default layout file
	 */
	const DEFAULT_LAYOUT_FILE = 'layout.php';

	/**
	 * Router
	 * @var Html5Wiki_Routing_Interface_Router
	 */
	protected $router = null;

	/**
	 * Template object
	 * @var Html5Wiki_Template_Decorator
	 */
	protected $template = null;

	/**
	 * Layout template object
	 * @var Html5Wiki_Template_Decorator
	 */
	protected $layoutTemplate = null;

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
		$this->layoutFile = self::DEFAULT_LAYOUT_FILE;
		
		$this->layoutTemplate = new Html5Wiki_Template_Php();
		$this->layoutTemplate->setTemplateFile($this->layoutFile);

		$this->template = new Html5Wiki_Template_Php($this->layoutTemplate);
	}

    public function dispatch(Html5Wiki_Routing_Interface_Router $router) {
		$this->router = $router;

		$this->setTemplate(strtolower($this->router->getAction()) . ".php");
		
		$this->layoutTemplate->assign('basePath', $this->router->getRequest()->getBasePath());

		$actionMethod = $this->router->getAction() . 'Action';

		if (method_exists($this, $actionMethod)) {
			return $this->$actionMethod();
		}
		throw new Html5Wiki_Exception_404('Invalid action "' . $actionMethod . '" in class "' . get_class($this) .'"');
	}

	/**
	 * Set Page title
	 * @param string $title
	 */
	protected function setTitle($title) {
		$this->layoutTemplate->assign('title', $title);
	}
	
	protected function setNoLayout() {
		$this->layoutTemplate = null;
	}

	/**
	 * Sets template file according to the controller directory.
	 * @param string $templateFile
	 */
	protected function setTemplate($templateFile) {
		$this->templateFile = strtolower($this->router->getController()) . DIRECTORY_SEPARATOR . $templateFile;
		$this->template->setTemplateFile($this->templateFile);
	}

	public function render() {
		$this->template->render();
	}
}
?>
