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
abstract class Html5Wiki_Template_Decorator implements Html5Wiki_Template_Interface {

	const TEMPLATE_PATH = 'templates/';

	/**
	 * Assigned variables.
	 * @var array
	 */
	protected $data = array();

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
}
?>
