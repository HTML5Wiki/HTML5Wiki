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
	
	/**
	 * Installation's base path
	 * @var string
	 */
	protected $basePath = '';
	
	/**
	 * Configuration
	 * @var Zend_Config
	 */
	protected $config = null;

	/**
	 *
	 * @param string $basePath 
	 */
	public function __construct() {
		$this->layoutFile = self::DEFAULT_LAYOUT_FILE;
		
		$this->layoutTemplate = new Html5Wiki_Template_Php();
		$this->layoutTemplate->setTemplateFile($this->layoutFile);

		$this->template = new Html5Wiki_Template_Php($this->layoutTemplate);
	}
	
	public function setBasePath($basePath) {
		$this->basePath = $basePath;
	}
	
	public function setConfig(Zend_Config $config) {
		$this->config = $config;
	}

    public function dispatch(Html5Wiki_Routing_Interface_Router $router) {
		$this->router = $router;
				
		$this->setTranslation();

		$this->setTemplate(strtolower($this->router->getAction()) . ".php");
		
		$this->layoutTemplate->assign('basePath', $this->router->getRequest()->getBasePath());
		$this->template->assign('basePath', $this->router->getRequest()->getBasePath());

		$actionMethod = $this->router->getAction() . 'Action';

		if (method_exists($this, $actionMethod)) {
			return $this->$actionMethod();
		}
		throw new Html5Wiki_Exception_404('Invalid action "' . $actionMethod . '" in class "' . get_class($this) .'"');
	}
	
	public function setTranslation() {;
		$language = Html5Wiki_Routing_Request::parseHttpAcceptLanguage($this->router->getRequest()->getLanguage(), 
						$this->config->languages->toArray());
		$language = ($language !== null) ? $language : $this->config->defaultLanguage;
		
		$translate = new Zend_Translate(
				array(
					'adapter' => 'array',
					'content' => $this->basePath . '/languages/' . $language . '.php',
					'locale'  => $language
				)
		);
		
		$this->layoutTemplate->setTranslate($translate);
		$this->template->setTranslate($translate);
	}
	
	

	/**
	 * Set Page title
	 * @param string $title
	 */
	protected function setTitle($title) {
		$this->layoutTemplate->assign('title', $title);
	}
	
	/**
	 * Disable layout
	 */
	protected function setNoLayout() {
		$this->layoutTemplate = null;
		$this->template->setNoLayout();
		$this->template->assign('ajax', true);
	}

	/**
	 * Sets template file according to the controller directory.
	 * @param string $templateFile
	 */
	protected function setTemplate($templateFile) {
		$this->templateFile = strtolower($this->router->getController()) . DIRECTORY_SEPARATOR . $templateFile;
		$this->template->setTemplateFile($this->templateFile);
	}

	/**
	 * @return void
	 */
	public function render() {
		$this->template->render();
	}

	/**
	 * Get permalink from url
	 *
	 * Works like this:
	 * User requests /wiki/foobar
	 * -> Method returns foobar, because the Action foobar doesn't exist.
	 * User requests /wiki/edit/foobar
	 * -> Method returns also foobar -> it knows that the action edit exists, so it adds this to the
	 *    needle of the substring replacement.
	 *
	 * @return string
	 */
	protected function getPermalink() {
		$uri = $this->router->getRequest()->getUri();
		$basePath = $this->router->getRequest()->getBasePath();
		$basePath .= '/';

		$needle = $basePath . $this->router->getController() . '/';
		$needle .= method_exists($this, $this->router->getAction() . 'Action') ? $this->router->getAction() . '/' : '';

		$permalink = substr_replace($uri, '', strpos($uri, $needle), strlen($needle));

		return $permalink;
	}
		
}
?>
