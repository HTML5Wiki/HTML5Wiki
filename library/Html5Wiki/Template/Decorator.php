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
 * Template decorator
 */
abstract class Html5Wiki_Template_Decorator implements Html5Wiki_Template_Interface {
	/**
	 * Helper class prefix where all helpers lie in.
	 * @todo Do this in a more elegant way
	 * 
	 * @var string
	 */
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
	 * Escapes a string value and prepares it to display in a template.
	 *
	 * @param unescaped database value
	 * @return escaped value
	 */
	public function escape($string) {
		$result = stripslashes($string);
		
		return $result;
	}
	
	/**
	 * Magic function for calling a view helper
	 * @param string $name
	 * @param string $args
	 * @return Html5Wiki_View_Helper 
	 */
	public function __call($name, $args) {
		if (!isset($this->helpers[$name])) {
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
	 * Get an assigned variable, the translator (if name is translate) or the response (if name is response).
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name) {
		$value = null;
		
		if ($name === 'translate') {
			$value = $this->translate;
		} else if($name === 'response') {
			$value = $this->response;
		}
		
		if(isset($this->data[$name])) {
			$value = $this->data[$name];
		}
		
		return $value;
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
