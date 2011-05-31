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
	 * Cookies list
	 * @var array
	 */
	private $cookies = array();
	
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
	 * Push cookie to the cookie list.
	 * 
	 * @param string $name
	 * @param string $value
	 * @param int $expire
	 * @param string $path
	 * @param string $domain
	 * @param bool $secure
	 * @param bool $httponly 
	 */
	public function pushCookie($name, $value, $expire = 0, $path = '', $domain = '', $secure = false, $httponly = false) {
		$this->cookies[] = array(
			'name' => $name,
			'value' => $value,
			'expire' => intval($expire),
			'path' => $path,
			'domain' => $domain,
			'secure' => (bool)$secure,
			'httponly' => (bool)$httponly
		);
	}
	
	/**
	 * Echoes the data
	 * @param string $data 
	 */
	protected function renderData($data) {
		echo $data;
	}
	
	/**
	 * Calls the php function "header" to render a specific header.
	 * 
	 * @param array $header
	 */
	protected function renderHeader($header) {
		header($header['string'], $header['replace'], $header['httpResponseCode']);
	}
	
	/**
	 * Calls the php function "setcookie" to render a specific cookie.
	 * @param array $cookie 
	 */
	protected function renderCookie($cookie) {
		setcookie($cookie['name'], $cookie['value'], $cookie['expire'], $cookie['path'], 
				$cookie['domain'], $cookie['secure'], $cookie['httponly']);
	}
	
	/**
	 * Renders all headers and data
	 */
	public function render() {
		foreach ($this->headers as $header) {
			$this->renderHeader($header);
		}
		foreach ($this->cookies as $cookie) {
			$this->renderCookie($cookie);
		}
		
		$this->renderData($this->data);
	}
	
	/**
	 * Get data
	 * @return string
	 */
	public function getData() {
		return $this->data;
	}
	
	/**
	 * Get headers
	 * @return array
	 */
	public function getHeaders() {
		return $this->headers;
	}
}
?>
