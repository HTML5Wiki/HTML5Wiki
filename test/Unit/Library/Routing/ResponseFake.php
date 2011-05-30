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
	
	/**
	 * Puts headers in to the renderedHeader field.
	 * 
	 * @override
	 * 
	 * @param string $header
	 * @param bool $replace
	 * @param int $httpResponseCode 
	 */
	public function renderHeader($header, $replace, $httpResponseCode) {
		$this->renderedHeader .= $header . "\n";
	}
	
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
}
?>
