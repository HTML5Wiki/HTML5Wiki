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
	 * Constructs a new request object
	 */
	public function __construct() {
		
	}

	/**
	 * Parses information from environment
	 */
	public function parse() {

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
}

?>
