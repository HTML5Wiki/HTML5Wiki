<?php

require_once 'library/Html5Wiki/Routing/Interface/Request.php';
require_once 'library/Html5Wiki/Routing/Request.php';

/**
 * Request test
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @package Test
 * @subpackage Routing
 */
class Test_Unit_Routing_RequestTest extends PHPUnit_Framework_TestCase {
	/**
	 * Default script name
	 */
	const SCRIPT_NAME = '/index.php';

	/**
	 * Request object used for testing
	 * @var Html5Wii_Routing_Interface_Request
	 */
	private $request = null;

	/**
	 * Base server variables to be merged with server variables in each testcase
	 * @var array
	 */
	private $baseServerVariables = array();

	/**
	 * Setup a new request object
	 */
	public function setUp() {
		$this->request = new Html5Wiki_Routing_Request();
		$this->baseServerVariables = array(
			'SERVER_NAME' => 'localhost',
			'SERVER_PORT' => '80',
			'HTTPS' => false,
			'QUERY_STRING' => '',
			'HTTP_USER_AGENT' => 'TestCase',
			'REMOTE_ADDR' => '127.0.0.1',
			'REQUEST_METHOD' => 'GET',
			'PHP_SELF' => self::SCRIPT_NAME,
			'REQUEST_URI' => '/'
		);
	}

	/**
	 * Tests a request to http://localhost/
	 */
	public function testBasePathEmptyWhenNotUsingSubdirInRootUrl() {
		$this->setUpRequestVariablesAndParseRequest(array(
			'PHP_SELF' => self::SCRIPT_NAME,
			'REQUEST_URI' => '/'
		));

		$this->assertEquals('', $this->request->getBasePath());
	}

	/**
	 * Tests a request to http://localhost/html5wiki/
	 * (Happens when the wiki is not within the root folder of the document root)
	 */
	public function testBasePathNotEmptyWhenUsingSubidrInRootUrl() {
		$path = '/html5wiki/';
		$this->setUpRequestVariablesAndParseRequest(array(
			'PHP_SELF' => $path . self::SCRIPT_NAME,
			'REQUEST_URI' => $path
		));

		$this->assertEquals($path, $this->request->getBasePath());
	}

	/**
	 * Tests a request to http://localhost/index.php
	 */
	public function testBasePathEmptyWithIndexPhp() {
		$this->setUpRequestVariablesAndParseRequest(array(
			'PHP_SELF' => self::SCRIPT_NAME,
			'REQUEST_URI' => self::SCRIPT_NAME
		));

		$this->assertEquals('', $this->request->getBasePath());
	}

	/**
	 * Tests a request to http://localhost/html5wiki/index.php
	 */
	public function testBasePathNotEmptyWithIndexPhp() {
		$path = '/html5wiki/';
		$this->setUpRequestVariablesAndParseRequest(array(
			'PHP_SELF' => $path . self::SCRIPT_NAME,
			'REQUEST_URI' => $path . self::SCRIPT_NAME
		));

		$this->assertEquals($path, $this->request->getBasePath());
	}

	/**
	 * Tests the arguments parsing of a request to http://localhost/
	 */
	public function testArgumentsEmpty() {
		$this->setUpRequestVariablesAndParseRequest(array(
			'PHP_SELF' => self::SCRIPT_NAME,
			'REQUEST_URI' => '/'
		));

		$this->assertEquals(0, count($this->request->getArguments()));
	}

	/**
	 * Tests the arguments parsing of a request to http://localhost/wiki/test
	 */
	public function testArgumentsNotEmpty() {
		$this->setUpRequestVariablesAndParseRequest(array(
			'PHP_SELF' => self::SCRIPT_NAME,
			'REQUEST_URI' => '/wiki/test'
		));

		$arguments = $this->request->getArguments();
		$this->assertEquals('wiki', $arguments[1]);
		$this->assertEquals('test', $arguments[2]);
	}

	/**
	 * Tests the arguments parsing of a request to http://localhost/index.php/wiki/test
	 */
	public function testArgumentsCorrectWithIndexPhp() {
		$this->setUpRequestVariablesAndParseRequest(array(
			'PHP_SELF' => self::SCRIPT_NAME,
			'REQUEST_URI' => self::SCRIPT_NAME . '/wiki/test'
		));

		$arguments = $this->request->getArguments();
		$this->assertEquals('wiki', $arguments[1]);
		$this->assertEquals('test', $arguments[2]);
	}

	public function testHost() {
		$this->setUpRequestVariablesAndParseRequest();
		$this->assertEquals('localhost', $this->request->getHost());
	}

	public function testPort() {
		$this->setUpRequestVariablesAndParseRequest();
		$this->assertEquals(80, $this->request->getPort());
	}

	public function testHttps() {
		$this->setUpRequestVariablesAndParseRequest();
		$this->assertEquals(false, $this->request->getHttps());
	}

	public function testQueryString() {
		$this->setUpRequestVariablesAndParseRequest();
		$this->assertEquals('', $this->request->getQueryString());
	}

	public function testUri() {
		$this->setUpRequestVariablesAndParseRequest(array(
			'REQUEST_URI' => self::SCRIPT_NAME . '/wiki/test'
		));

		$this->assertEquals(self::SCRIPT_NAME . '/wiki/test', $this->request->getUri());
	}

	/**
	 * Helper method to fully setup the request
	 * 
	 * @param array $serverVariables
	 */
	private function setUpRequestVariablesAndParseRequest($serverVariables = array()) {
		$serverVariables = array_merge($this->baseServerVariables, $serverVariables);
		$this->request->setServerVariables($serverVariables);
		$this->request->parse();
	}

	/**
	 * Resets the request object
	 */
	public function tearDown() {
		$this->request = null;
	}

}

?>
