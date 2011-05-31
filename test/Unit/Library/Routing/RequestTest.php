<?php

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
			'REQUEST_URI' => '/',
			'HTTP_ACCEPT_LANGUAGE' => 'de-de,de;q=0.8,en-us;q=0.5,en;q=0.3'
		);
	}
	
	public function testGetServerVariables() {
		$this->setUpRequestVariablesAndParseRequest();
		$this->assertEquals($this->baseServerVariables, $this->request->getServerVariables());
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
	
	public function testIsAjax() {
		$this->setUpRequestVariablesAndParseRequest();
		$this->assertFalse($this->request->isAjax());
		
		$this->setUpRequestVariablesAndParseRequest(array(
			'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'
		));
		$this->assertTrue($this->request->isAjax());
	}
	
	public function testParseHttpAcceptLanguage() {
		$this->setUpRequestVariablesAndParseRequest();
		
		$_SERVER['HTTP_ACCEPT_LANGUAGE'] = $this->request->getLanguage(); // Zend_Locale sadly uses this global.

		$language = Html5Wiki_Routing_Request::parseHttpAcceptLanguage(array('de', 'en'));
		$this->assertEquals('de', $language);
		
		$language = Html5Wiki_Routing_Request::parseHttpAcceptLanguage(array('en'));
		$this->assertEquals('en', $language);
		
		$language = Html5Wiki_Routing_Request::parseHttpAcceptLanguage(array());
		$this->assertEquals('', $language);
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
	
	public function testPath() {
		$this->setUpRequestVariablesAndParseRequest(array(
			'PATH_INFO' => '/tmp/',
		));
		
		$this->assertEquals('/tmp/', $this->request->getPath());
	}
	
	public function testRequestMethod() {
		$this->setUpRequestVariablesAndParseRequest(array(
			'REQUEST_METHOD' => 'POST',
		));
		
		$this->assertEquals('post', $this->request->getRequestMethod());
	}
	
	public function testGetGet() {
		$this->setUpRequestVariablesAndParseRequest();
		$this->request->setGet(array('foo' => 'bar'));
		
		$this->assertEquals('bar', $this->request->getGet('foo'));
		$this->assertNull($this->request->getGet('test'));
		$this->assertEquals('defaultValue', $this->request->getGet('test', 'defaultValue'));
	}
	
	public function testGetPost() {
		$this->setUpRequestVariablesAndParseRequest();
		$this->request->setPost(array('foo' => 'bar'));
		
		$this->assertEquals('bar', $this->request->getPost('foo'));
		$this->assertNull($this->request->getPost('test'));
		$this->assertEquals('defaultValue', $this->request->getPost('test', 'defaultValue'));
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
		unset($this->request);
	}

}

?>
