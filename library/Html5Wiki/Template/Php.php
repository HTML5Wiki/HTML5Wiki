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
 * Description of Template
 *
 * @author michael
 */
class Html5Wiki_Template_Php extends Html5Wiki_Template_Decorator {

	private $templateFile = '';
	private $decoratedContent = '';

	/**
	 * Template File to render
	 * @param string $templateFile
	 */
	public function setTemplateFile($templateFile) {
		$this->templateFile = $templateFile;
	}

	public function setDecoratedContent($decoratedContent) {
		$this->decoratedContent = $decoratedContent;
	}

	public function __get($name) {
		return isset($this->data[$name]) ? $this->data[$name] : null;
	}

	public function render() {
		if (empty($this->templateFile)) {
			throw new Html5Wiki_Exception_Template('Unable to perform rendering without a templateFile');
		}

		ob_start();
		include_once(self::TEMPLATE_PATH . $this->templateFile);
		$decoratedContent = ob_get_clean();

		if ($this->decoratedTemplate instanceof Html5Wiki_Template_Interface) {
			$this->decoratedTemplate->setDecoratedContent($decoratedContent);
			$this->decoratedTemplate->render();
		} else {
			echo $decoratedContent;
		}
	}

}

?>