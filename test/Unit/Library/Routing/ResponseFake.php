<?php

/**
 * Response fake for overriding the render methods
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @package Test
 * @subpackage Routing
 */
class Test_Unit_Library_Routing_ReponseFake extends Html5Wiki_Routing_Response {
	
	public $renderedData = '';
	public $renderedHeader = '';
	public $renderedCookie = '';
	
	/**
	 * Puts data into the renderedData field.
	 * 
	 * @override
	 * 
	 * @param string $data 
	 */
	public function renderData($data) {
		$this->renderedData .= $data;
	}
	
	/**
	 * Puts headers in to the renderedHeader field.
	 * 
	 * @override
	 * 
	 * @param array $header
	 */
	public function renderHeader($header) {
		$this->renderedHeader .= implode(",", $header) . "\n";
	}
	
	/**
	 * Puts cookies in to the renderedCookie field.
	 * 
	 * @override
	 * 
	 * @param array $cookie 
	 */
	public function renderCookie($cookie) {
		$this->renderedCookie .= implode(",", $cookie) . "\n";
	}
}
?>
