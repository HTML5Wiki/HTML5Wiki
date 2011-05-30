<?php

/**
 * Response capsules all data to be responded.
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @package Library
 * @subpackage Routing
 */
class Html5Wiki_Routing_Response {
	/**
	 * Output data
	 * @var string
	 */
	private $data = '';
	
	/**
	 * Headers list
	 * @var array
	 */
	private $headers = array();
	
	/**
	 * Push new data to the end of the existing data.
	 * @param string $data 
	 */
	public function pushData($data) {
		$this->data .= $data;
	}
	
	/**
	 * Push a new header info to the headers list.
	 * @param string $header 
	 */
	public function pushHeader($header, $replace = true, $httpResponseCode = 0) {
		$this->headers[] = array(
			'string' => $header, 
			'replace' => $replace, 
			'httpResponseCode' => $httpResponseCode
		);
	}
	
	/**
	 * Calls the php function "header" to render a specific header
	 */
	protected function renderHeader($header, $replace, $httpResponseCode) {
		header($header, $replace, $httpResponseCode);
	}
	
	/**
	 * Echoes the data
	 * @param string $data 
	 */
	protected function renderData($data) {
		echo $data;
	}
	
	/**
	 * Renders all headers and data
	 */
	public function render() {
		foreach ($this->headers as $header) {
			$this->renderHeader($header['string'], $header['replace'], $header['httpResponseCode']);
		}
		
		$this->renderData($this->data);
	}
}
?>
