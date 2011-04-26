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

	/**
	 * Assigned variables.
	 * @var array
	 */
	protected $data = array();
	
	protected $decoratedTemplate = null;

	public function __construct(Html5Wiki_Template_Interface $decoratedTemplate = null) {
		$this->decoratedTemplate = $decoratedTemplate;
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

	public function render() {
		// can be null
		if ($this->decoratedTemplate instanceof Html5Wiki_Template_Interface) {
			$this->decoratedTemplate->render();
		}
	}
}
?>
