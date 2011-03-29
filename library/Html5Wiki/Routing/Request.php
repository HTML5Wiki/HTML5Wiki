<?php

/**
 * Request parses informations sent by the server
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @package Library
 * @subpackage Routing
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
	 * Base Path
	 * @var string
	 */
	private $basePath = '';

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
	 * $_SERVER variables
	 * @var array
	 */
	private $serverVariables = array();

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
		$this->serverVariables = $_SERVER;
	}

	/**
	 * Parses server variables and sets them accordingly our use
	 */
	public function parse() {
		$this->host = $this->serverVariables['SERVER_NAME'];
		$this->port = $this->serverVariables['SERVER_PORT'];
		$this->https = !empty($this->serverVariables['HTTPS']);
		$this->uri = $this->serverVariables['REQUEST_URI'];

		$this->path = isset($this->serverVariables['PATH_INFO']) ? $this->serverVariables['PATH_INFO'] : '';

		$phpSelf = $this->serverVariables['PHP_SELF'];
		$indexPhpPos = strpos($phpSelf, '/index.php');
		$this->basePath = substr($phpSelf, 0, $indexPhpPos);

		$this->queryString = $this->serverVariables['QUERY_STRING'];
		$this->userAgent = $this->serverVariables['HTTP_USER_AGENT'];
		$this->ipAddress = $this->serverVariables['REMOTE_ADDR'];
		$this->requestMethod = $this->serverVariables['REQUEST_METHOD'];

		// strip base path & index.php from request uri to get only the relevant parts
		$arguments = str_replace($this->basePath, '', str_replace('/index.php', '', $this->uri));
		$this->arguments = explode("/", $arguments);
		$this->arguments = array_filter($this->arguments);

		$this->post = $_POST;
		$this->get = $_GET;
	}


	/**
	 *
	 * @return array
	 */
	public function getServerVariables() {
		return $this->serverVariables;
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
	 *
	 * @return string
	 */
	public function getBasePath() {
		return $this->basePath;
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

	/**
	 *
	 * @param array $serverVariables
	 */
	public function setServerVariables($serverVariables) {
		$this->serverVariables = $serverVariables;
	}

	/**
	 *
	 * @param string $host
	 */
	public function setHost($host) {
		$this->host = $host;
	}

	/**
	 *
	 * @param int $port
	 */
	public function setPort($port) {
		$this->port = $port;
	}

	/**
	 *
	 * @param bool $https
	 */
	public function setHttps($https) {
		$this->https = $https;
	}

	/**
	 *
	 * @param string $uri
	 */
	public function setUri($uri) {
		$this->uri = $uri;
	}

	/**
	 *
	 * @param string $path
	 */
	public function setPath($path) {
		$this->path = $path;
	}

	/**
	 *
	 * @param string $basePath
	 */
	public function setBasePath($basePath) {
		$this->basePath = $basePath;
	}

	/**
	 *
	 * @param string $queryString
	 */
	public function setQueryString($queryString) {
		$this->queryString = $queryString;
	}

	/**
	 *
	 * @param string $userAgent
	 */
	public function setUserAgent($userAgent) {
		$this->userAgent = $userAgent;
	}

	/**
	 *
	 * @param string $ipAddress
	 */
	public function setIpAddress($ipAddress) {
		$this->ipAddress = $ipAddress;
	}

	/**
	 *
	 * @param string $requestMethod
	 */
	public function setRequestMethod($requestMethod) {
		$this->requestMethod = $requestMethod;
	}

	/**
	 *
	 * @param array $arguments
	 */
	public function setArguments($arguments) {
		$this->arguments = $arguments;
	}

	/**
	 *
	 * @param array $post
	 */
	public function setPost($post) {
		$this->post = $post;
	}

	/**
	 *
	 * @param array $get
	 */
	public function setGet($get) {
		$this->get = $get;
	}

}

?>
