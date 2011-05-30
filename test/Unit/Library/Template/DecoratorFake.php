<?php
/**
 * Template Decorator fake for setting the headers
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @package Test
 * @subpackage Template
 */
class Test_Unit_Library_Template_DecoratorFake extends Html5Wiki_Template_Decorator {
	
	public $headers = array();
	
	/**
	 * @override
	 * Overrides Html5Wiki_Template_Decorator#setHeader to prevent headers already sent errors.
	 * Puts all set header calls into the public variable "headers"
	 * 
	 * @param string $header 
	 */
	public function setHeader($header) {
		$this->headers[] = $header;
	}
}

?>
