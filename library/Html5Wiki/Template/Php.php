<?php
/**
 * PHP Templating decorator
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @package Library
 * @subpackage Template
 */
class Html5Wiki_Template_Php extends Html5Wiki_Template_Decorator {

	private $templateFile = '';

	/**
	 * Template File to render
	 * @param string $templateFile
	 */
	public function setTemplateFile($templateFile) {
		$this->templateFile = $templateFile;
	}
	
	public function setNoLayout() {
		$this->decoratedTemplate = null;
	}

	public function render() {
		if (empty($this->templateFile)) {
			throw new Html5Wiki_Exception_Template('Unable to perform rendering without a templateFile');
		}

		ob_start();
		include_once(self::TEMPLATE_PATH . $this->templateFile);
		$decoratedContent = ob_get_clean();
		

		// can be null
		if ($this->decoratedTemplate instanceof Html5Wiki_Template_Interface) {
			$this->decoratedTemplate->setDecoratedContent($decoratedContent);
			$this->decoratedTemplate->render();
		} else {
			if ($this->response) {
				$this->response->pushData($decoratedContent);
			} else {
				echo $decoratedContent;
			}
		}
	}

}

?>
