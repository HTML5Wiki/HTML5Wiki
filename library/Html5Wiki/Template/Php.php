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
 * @subpackage Template
 */

/**
 * PHP Templating decorator
 */
class Html5Wiki_Template_Php extends Html5Wiki_Template_Decorator {
	
	/**
	 * Standard template path
	 * @var string
	 */
	const TEMPLATE_PATH = 'templates/';
	
	private $templatePath = '';
	private $templateFile = '';
	
	public function __construct(Html5Wiki_Routing_Response $response, Html5Wiki_Template_Interface $decoratedTemplate = null) {
		parent::__construct($response, $decoratedTemplate);
		
		$this->templatePath = self::TEMPLATE_PATH;
	}

	/**
	 * Template File to render
	 * @param string $file
	 */
	public function setTemplateFile($file) {
		$this->templateFile = $file;
	}
	
	/**
	 * Set template path
	 * @param string $path 
	 */
	public function setTemplatePath($path) {
		$this->templatePath = $path;
	}
	
	/**
	 * Do not render the decorated template
	 */
	public function setNoLayout() {
		$this->decoratedTemplate = null;
	}

	public function render() {
		if (empty($this->templateFile)) {
			throw new Html5Wiki_Exception_Template('Unable to perform rendering without a templateFile');
		}
		ob_start();
		include($this->templatePath . $this->templateFile);
		$decoratedContent = ob_get_clean();
		

		// can be null
		if ($this->decoratedTemplate instanceof Html5Wiki_Template_Interface) {
			$this->decoratedTemplate->setDecoratedContent($decoratedContent);
			$this->decoratedTemplate->render();
		} else {
			$this->response->pushData($decoratedContent);
		}
	}

}

?>
