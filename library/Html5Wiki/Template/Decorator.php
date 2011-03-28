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

	const TEMPLATE_PATH = 'templates/';

	/**
	 * Assigned variables.
	 * @var array
	 */
	protected $data = array();
	
	protected $decoratedTemplate = null;

	public function __construct(Html5Wiki_Template_Interface $decoratedTemplate = null) {
		$this->decoratedTemplate = $decoratedTemplate;
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
		if ($this->decoratedTemplate instanceof Html5Wiki_Template_Interface) {
			$this->decoratedTemplate->render();
		}
	}
}
?>
