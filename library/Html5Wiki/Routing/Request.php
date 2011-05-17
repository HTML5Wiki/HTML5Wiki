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
	 * Is ajax request? (X-Requested-With = XMLHttpRequest)
	 * @var bool
	 */
	private $isAjaxRequest = false;

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
	 * Language of the requestor
	 * @var string
	 */
	private $language = '';

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
	 * Application configuration
	 * @var Zend_Config
	 */
	private $config = null;

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
	 * Cookies
	 * @var array
	 */
	private $cookie = array();

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
		
		$this->isAjaxRequest = isset($this->serverVariables['HTTP_X_REQUESTED_WITH']) 
									&& $this->serverVariables['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
		
		$this->language = $this->serverVariables['HTTP_ACCEPT_LANGUAGE'];

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
		$arguments = str_replace('?' . $this->queryString, '', $arguments);
		
		$this->arguments = explode("/", $arguments);
		$this->arguments = array_filter($this->arguments);

		$this->post = $_POST;
		$this->get = $_GET;
		$this->cookie = $_COOKIE;
	}
	
	/**
	 * Parses the HTTP_ACCEPT_LANGUAGE string and matches the portions against the system languages given. 
	 * On first match, it returns the language.
	 * If no match, it returns null
	 * 
	 * @param string $languageString
	 * @param array  $systemLanguages
	 * @param string|null
	 */
	public static function parseHttpAcceptLanguage($languageString, array $systemLanguages) {
		$locale = new Zend_Locale();
		$validLanguages = $locale->getBrowser();
		foreach ($validLanguages as $language => $quantity) {
			if (in_array($language, $systemLanguages)) {
				return $language;
			}
		}
		
		return null;
	}


	/**
	 * Get all server variables
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
	 * If request was a ajax request
	 * @return bool 
	 */
	public function isAjax() {
		return $this->isAjaxRequest;
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
	 * Gets base path of uri
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
	 * Get the whole POST paramters from the request
	 * 
	 * @author	Nicolas Karrer <nkarrer@hsr.ch>
	 * @return	array
	 */
	public function getPostParameters() {
		return $this->post;
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
	 * Get the whole GET paramters from the request
	 * 
	 * @author	Nicolas Karrer <nkarrer@hsr.ch>
	 * @return	array
	 */
	public function getGetParameters() {
		return $this->get;
	}
	
	/**
	 * Get a COOKIE. If it doesn't exist, return default.
	 * 
	 * @param string $key
	 * @param mixed $default [optional, default null]
	 * 
	 * @return mixed
	 */
	public function getCookie($key, $default = null) {
		return isset($this->cookie[$key]) ? $this->cookie[$key] : $default;
	}
	
	/**
	 * Set server variables
	 * @param array $serverVariables
	 */
	public function setServerVariables(array $serverVariables) {
		$this->serverVariables = $serverVariables;
	}

	/**
	 * Set host
	 * @param string $host
	 */
	public function setHost($host) {
		$this->host = $host;
	}

	/**
	 * Set Port
	 * @param int $port
	 */
	public function setPort($port) {
		$this->port = $port;
	}

	/**
	 * Set https
	 * @param bool $https
	 */
	public function setHttps($https) {
		$this->https = $https;
	}

	/**
	 * Set uri
	 * @param string $uri
	 */
	public function setUri($uri) {
		$this->uri = $uri;
	}

	/**
	 * Set path
	 * @param string $path
	 */
	public function setPath($path) {
		$this->path = $path;
	}

	/**
	 * Set base path
	 * @param string $basePath
	 */
	public function setBasePath($basePath) {
		$this->basePath = $basePath;
	}

	/**
	 * Set query string
	 * @param string $queryString
	 */
	public function setQueryString($queryString) {
		$this->queryString = $queryString;
	}

	/**
	 * Set user agent
	 * @param string $userAgent
	 */
	public function setUserAgent($userAgent) {
		$this->userAgent = $userAgent;
	}

	/**
	 * Set Ip address
	 * @param string $ipAddress
	 */
	public function setIpAddress($ipAddress) {
		$this->ipAddress = $ipAddress;
	}

	/**
	 * Set request method
	 * @param string $requestMethod
	 */
	public function setRequestMethod($requestMethod) {
		$this->requestMethod = $requestMethod;
	}

	/**
	 * Set arguments
	 * @param array $arguments
	 */
	public function setArguments(array $arguments) {
		$this->arguments = $arguments;
	}

	/**
	 * Set post
	 * @param array $post
	 */
	public function setPost(array $post) {
		$this->post = $post;
	}

	/**
	 * Set get
	 * @param array $get
	 */
	public function setGet(array $get) {
		$this->get = $get;
	}
	
	/**
	 * Set language
	 * @param string $language 
	 */
	public function setLanguage($language) {
		$this->language = $language;
	}
	
	/**
	 * Get language
	 * @return string
	 */
	public function getLanguage() {
		return $this->language;
	}
}

?>
