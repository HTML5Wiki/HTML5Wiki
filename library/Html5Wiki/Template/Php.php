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

	const HELPER_CLASS_PREFIX = 'Html5Wiki_View_';

	private $templateFile = '';
	private $decoratedContent = '';

	private $helpers = array();

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

	public function __call($name, $args) {
		if (!in_array($name, $this->helpers)) {
			$helper = $this->getHelper($name);
			$this->helpers[$name] = $helper;
		}
		return call_user_func(array($helper, $name), $args);
	}

	private function getHelper($name) {
		$name = ucfirst($name);
		$className = self::HELPER_CLASS_PREFIX . $name;
		return new $className($this);
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
			echo $decoratedContent;
		}
	}

}

?>
