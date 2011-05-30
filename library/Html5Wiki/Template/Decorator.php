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
	
	/**
	 * Response object
	 * @var Html5Wiki_Routing_Response 
	 */
	protected $response = null;
	
	/**
	 * Registered helpers
	 * @var array
	 */
	private $helpers = array();
	
	/**
	 * Translate object
	 * @var Zend_Translate
	 */
	private $translate = null;
	
	/**
	 * Decorated content
	 * @var string
	 */
	protected $decoratedContent = '';

	/**
	 * Assigned variables.
	 * @var array
	 */
	protected $data = array();
	
	/**
	 * Decorated template
	 * @var string
	 */
	protected $decoratedTemplate = null;

	/**
	 * Setup decorator. 
	 * 
	 * @param Html5Wiki_Routing_Response $response
	 * @param Html5Wiki_Template_Interface $decoratedTemplate If not null, render the template around the mentioned one.
	 */
	public function __construct(Html5Wiki_Routing_Response $response, Html5Wiki_Template_Interface $decoratedTemplate = null) {
		$this->response = $response;
		$this->decoratedTemplate = $decoratedTemplate;
	}
	
	/**
	 * Set translator
	 * @param Zend_Translate $translate 
	 */
	public function setTranslate(Zend_Translate $translate) {
		$this->translate = $translate;
	}
	
	/**
	 * Get translator
	 * @return Zend_Translate
	 */
	public function getTranslate() {
		return $this->translate;
	}
	
	/**
	 * Set response
	 * @param Html5Wiki_Routing_Response $response
	 */
	public function setResponse(Html5Wiki_Routing_Response $response) {
		$this->response = $response;
	}
	
	/**
	 * Get response
	 * @return Html5Wiki_Routing_Response
	 */
	public function getResponse() {
		return $this->response;
	}
	
	/**
	 * Magic function for calling a view helper
	 * @param string $name
	 * @param string $args
	 * @return Html5Wiki_View_Helper 
	 */
	public function __call($name, $args) {
		if (!in_array($name, $this->helpers)) {
			$helper = $this->getHelper($name);
			$this->helpers[$name] = $helper;
		} else {
			$helper = $this->helpers[$name];
		}
		return call_user_func(array($helper, $name), $args);
	}
	
	/**
	 * Get a helper instance
	 * @param string $name
	 * @return Html5Wiki_View_Helper 
	 */
	private function getHelper($name) {
		$name = ucfirst($name);
		$className = self::HELPER_CLASS_PREFIX . $name;
		return new $className($this);
	}
	
	/**
	 * Set the decorated content
	 * @param string $decoratedContent 
	 */
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
	
	/**
	 * Get an assigned variable or the translator (if name is translate)
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name) {
		if ($name === 'translate') {
			return $this->translate;
		} else if($name === 'response') {
			return $this->response;
		}
		
		return isset($this->data[$name]) ? $this->data[$name] : null;
	}
	
	/**
	 * Check if variable is set in data and/or is empty
	 * @param string $name
	 * @return boolean 
	 */
	public function __isset($name) {
		if ($name === 'translate' || $name === 'response' || (isset($this->data[$name]) && !empty($this->data[$name]))) {
			return true;
		}
		return false;
	}
}
?>
