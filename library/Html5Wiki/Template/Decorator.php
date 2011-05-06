<?php
/**
 * Template decorator
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @package Library
 * @subpackage Template
 */
abstract class Html5Wiki_Template_Decorator implements Html5Wiki_Template_Interface {
	
	const HELPER_CLASS_PREFIX = 'Html5Wiki_View_';
	const TEMPLATE_PATH = 'templates/';
	
	private $helpers = array();
	private $translate = null;
	protected $decoratedContent = '';

	/**
	 * Assigned variables.
	 * @var array
	 */
	protected $data = array();
	
	protected $decoratedTemplate = null;

	public function __construct(Html5Wiki_Template_Interface $decoratedTemplate = null) {
		$this->decoratedTemplate = $decoratedTemplate;
	}
	
	public function setTranslate(Zend_Translate $translate) {
		$this->translate = $translate;
	}
	
	public function getTranslate() {
		return $this->translate;
	}
	
	public function __call($name, $args) {
		if (!in_array($name, $this->helpers)) {
			$helper = $this->getHelper($name);
			$this->helpers[$name] = $helper;
		} else {
			$helper = $this->helpers[$name];
		}
		return call_user_func(array($helper, $name), $args);
	}
	
	private function getHelper($name) {
		$name = ucfirst($name);
		$className = self::HELPER_CLASS_PREFIX . $name;
		return new $className($this);
	}
	
	public function setDecoratedContent($decoratedContent) {
		$this->decoratedContent = $decoratedContent;
	}


	/**
	 * Assigned Variables
	 *
	 * Returns $this for easy method chaining
	 * 
	 * @param string $key
	 * @param mixed  $value
	 *
	 * @return Html5Wiki_Template_Decorator [$this]
	 */
	public function assign($key, $value) {
		$this->data[$key] = $value;

		return $this;
	}
	
	public function __get($name) {
		if ($name === 'translate') {
			return $this->translate;
		}
		return isset($this->data[$name]) ? $this->data[$name] : null;
	}
	
	/**
	 * Check if variable is set in data and/or is empty
	 * @param string $name
	 * @return boolean 
	 */
	public function __isset($name) {
		if ($name === 'translate' || (isset($this->data[$name]) && !empty($this->data[$name]))) {
			return true;
		}
		return false;
	}

	public function render() {
		// can be null
		if ($this->decoratedTemplate instanceof Html5Wiki_Template_Interface) {
			$this->decoratedTemplate->render();
		}
	}
}
?>
