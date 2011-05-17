<?php
/**
 * Request interface
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @package Library
 * @subpackage Routing
 */
interface Html5Wiki_Routing_Interface_Request {
	
	/**
	 * Constructs a new request object
	 */
    public function __construct();
	
	/**
	 * Parses server variables and sets them accordingly our use
	 */
	public function parse();
	
	/**
	 * Parses the HTTP_ACCEPT_LANGUAGE string and matches the portions against the system languages given. 
	 * On first match, it returns the language.
	 * If no match, it returns null
	 * 
	 * @param string $languageString
	 * @param array  $systemLanguages
	 * @param string|null
	 */
	public static function parseHttpAcceptLanguage($languageString, array $systemLanguages);
	
	
	/**
	 * Get all server variables
	 * @return array
	 */
	public function getServerVariables();
	
	/**
	 * Gets called host
	 * @return string
	 */
	public function getHost();
	
	/**
	 * Gets called port
	 * @return int
	 */
	public function getPort();
	
	/**
	 * If request was a https request
	 * @return boolean
	 */
	public function getHttps();
	
	/**
	 * Gets called uri
	 * @return string
	 */
	public function getUri();
	
	/**
	 * Get path from uri
	 * @return string
	 */
	public function getPath();
	
	/**
	 * Gets base path of uri
	 * @return string
	 */
	public function getBasePath();
	
	/**
	 * Query string
	 * @return string
	 */
	public function getQueryString();
	
	/**
	 * Get client's user agent
	 * @return string
	 */
	public function getUserAgent();
	
	/**
	 * Get clients ip address
	 * @return string
	 */
	public function getIpAddress();
	
	/**
	 * Request method
	 * @return string
	 */
	public function getRequestMethod();
	
	/**
	 * Get URL Arguments
	 * @return array
	 */
	public function getArguments();
	
	/**
	 * Get a POST key. If it doesn't exist, return default.
	 * 
	 * @param string $key
	 * @param mixed $default [optional, default null]
	 *
	 * @return mixed
	 */
	public function getPost($key, $default = null);
	
	/**
	 * Get the whole POST paramters form the request
	 * 
	 * @author	Nicolas Karrer <nkarrer@hsr.ch>
	 * @return	Array
	 */
	public function getPostParameters();
	
	/**
	 * Get a GET key. If it doesn't exist, return default.
	 *
	 * @param string $key
	 * @param mixed $default [optional, default null]
	 *
	 * @return mixed
	 */
	public function getGet($key, $default = null);
	
	/**
	 * Get the whole GET paramters form the request
	 * 
	 * @author	Nicolas Karrer <nkarrer@hsr.ch>
	 * @return	Array
	 */
	public function getGetParameters();
	
	/**
	 * Get a COOKIE. If it doesn't exist, return default.
	 * 
	 * @param string $key
	 * @param mixed $default [optional, default null]
	 * 
	 * @return mixed
	 */
	public function getCookie($key, $default = null);
	
	/**
	 * Set server variables
	 * @param array $serverVariables
	 */
	public function setServerVariables(array $serverVariables);
	
	/**
	 * Set host
	 * @param string $host
	 */
	public function setHost($host);
	
	/**
	 * Set port
	 * @param int $port
	 */
	public function setPort($port);
	
	/**
	 * Set https
	 * @param bool $https
	 */
	public function setHttps($https);
	
	/**
	 * Set uri
	 * @param string $uri
	 */
	public function setUri($uri);
	
	/**
	 * Set Path
	 * @param string $path
	 */
	public function setPath($path);
	
	/**
	 * Set base path
	 * @param string $basePath
	 */
	public function setBasePath($basePath);
	
	/**
	 * Set query string
	 * @param string $queryString
	 */
	public function setQueryString($queryString);
	
	/**
	 * Set user agent
	 * @param string $userAgent
	 */
	public function setUserAgent($userAgent);
	
	/**
	 * Set ip address
	 * @param string $ipAddress
	 */
	public function setIpAddress($ipAddress);
	
	/**
	 * Set request method
	 * @param string $requestMethod
	 */
	public function setRequestMethod($requestMethod);
	
	/**
	 * Set arguments
	 * @param array $arguments
	 */
	public function setArguments(array $arguments);
	
	/**
	 * Set post
	 * @param array $post
	 */
	public function setPost(array $post);
	
	/**
	 * Set get
	 * @param array $get
	 */
	public function setGet(array $get);
	
	/**
	 * Set language
	 * @param string $language 
	 */
	public function setLanguage($language);
	
	/**
	 * Get language
	 * @return string
	 */
	public function getLanguage();
}
?>
