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
 * Description of Request
 *
 * @author michael
 */
class Html5Wiki_Routing_Request implements Html5Wiki_Routing_Interface_Request {

	/**
	 * Called host
	 * @var string
	 */
	private $host = '';
	/**
	 * Called port
	 * @var int
	 */
	private $port = 0;
	/**
	 * Used https?
	 * @var bool
	 */
	private $https = false;
	/**
	 * Called uri
	 * @var string
	 */
	private $uri = '';
	/**
	 * Path from uri
	 * @var path
	 */
	private $path = '';
	/**
	 * Query string
	 * @var string
	 */
	private $queryString = '';
	/**
	 * Client user agent
	 * @var string
	 */
	private $userAgent = '';
	/**
	 * Client ip address
	 * @var string
	 */
	private $ipAddress = '';
	/**
	 * Request method
	 * @var string
	 */
	private $requestMethod = '';
	/**
	 * Arguments in the URL
	 * @var array
	 */
	private $arguments = array();
	/**
	 * POST Arguments
	 * @var array
	 */
	private $post = array();
	/**
	 * GET Arguments
	 * @var array
	 */
	private $get = array();

	/**
	 * Constructs a new request object
	 */
	public function __construct() {
		$this->host = $_SERVER['SERVER_NAME'];
		$this->port = $_SERVER['SERVER_PORT'];
		$this->https = !empty($_SERVER['HTTPS']);
		$this->uri = $_SERVER['REQUEST_URI'];
		$this->path = $_SERVER['PATH_INFO'];
		$this->queryString = $_SERVER['QUERY_STRING'];
		$this->userAgent = $_SERVER['HTTP_USER_AGENT'];
		$this->ipAddress = $_SERVER['REMOTE_ADDR'];
		$this->requestMethod = $_SERVER['REQUEST_METHOD'];

		$this->arguments = explode("/", $this->path);
		$this->post = $_POST;
		$this->get = $_GET;

		// unset $_POST/$_GET to forbid using those arrays directly
		unset($_POST, $_GET);
	}

	/**
	 * Gets called host
	 * @return string
	 */
	public function getHost() {
		return $this->host;
	}

	/**
	 * Gets called port
	 * @return int
	 */
	public function getPort() {
		return $this->port;
	}

	/**
	 * If request was a https request
	 * @return boolean
	 */
	public function getHttps() {
		return $this->https;
	}

	/**
	 * Gets called uri
	 * @return string
	 */
	public function getUri() {
		return $this->uri;
	}

	/**
	 * Get path from uri
	 * @return string
	 */
	public function getPath() {
		return $this->path;
	}

	/**
	 * Query string
	 * @return string
	 */
	public function getQueryString() {
		return $this->queryString;
	}

	/**
	 * Get client's user agent
	 * @return string
	 */
	public function getUserAgent() {
		return $this->userAgent;
	}

	/**
	 * Get clients ip address
	 * @return string
	 */
	public function getIpAddress() {
		return $this->ipAddress;
	}

	/**
	 * Request method
	 * @return string
	 */
	public function getRequestMethod() {
		return $this->requestMethod;
	}

	/**
	 * Get URL Arguments
	 * @return array
	 */
	public function getArguments() {
		return $this->arguments;
	}

	/**
	 * Get a POST key. If it doesn't exist, return default.
	 * 
	 * @param string $key
	 * @param mixed $default [optional, default null]
	 *
	 * @return mixed
	 */
	public function getPost($key, $default = null) {
		return isset($this->post[$key]) ? $this->post[$key] : $default;
	}

	/**
	 * Get a GET key. If it doesn't exist, return default.
	 *
	 * @param string $key
	 * @param mixed $default [optional, default null]
	 *
	 * @return mixed
	 */
	public function getGet($key, $default = null) {
		return isset($this->get[$key]) ? $this->get[$key] : $default;
	}

}

?>
