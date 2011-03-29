<?php
/**
 * Request test
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @package Test
 * @subpackage Routing
 */

require 'library/Html5Wiki/Routing/Interface/Request.php';
require 'library/Html5Wiki/Routing/Request.php';

class Test_Unit_Routing_RequestTest extends PHPUnit_Framework_TestCase {

	const SCRIPT_NAME = '/index.php';

	private $request;
	private $baseServerVariables;

    public function setUp() {
		$this->request = new Html5Wiki_Routing_Request();
		$this->baseServerVariables = array(
			'SERVER_NAME'  => 'localhost',
			'SERVER_PORT'  => '80',
			'HTTPS'        => false,
			'QUERY_STRING' => '',
			'HTTP_USER_AGENT' => 'TestCase',
			'REMOTE_ADDR'     => '127.0.0.1',
			'REQUEST_METHOD'  => 'GET'
		);
	}

	public function testBasePathEmptyWhenNotUsingSubdirInRootUrl() {
		$this->setUpRequestVariablesAndParseRequest(array(
			'PHP_SELF'    => self::SCRIPT_NAME,
			'REQUEST_URI' => '/'
		));

		$this->assertEquals('', $this->request->getBasePath());
	}

	public function testBasePathNotEmptyWhenUsingSubidrInRootUrl() {
		$path = '/html5wiki/';
		$this->setUpRequestVariablesAndParseRequest(array(
			'PHP_SELF'    => $path . self::SCRIPT_NAME,
			'REQUEST_URI' => $path
		));

		$this->assertEquals($path, $this->request->getBasePath());
	}

	public function testBasePathEmptyWithIndexPhp() {
		$this->setUpRequestVariablesAndParseRequest(array(
			'PHP_SELF'    => self::SCRIPT_NAME,
			'REQUEST_URI' => self::SCRIPT_NAME
		));

		$this->assertEquals('', $this->request->getBasePath());
	}

	public function testBasePathNotEmptyWithIndexPhp() {
		$path = '/html5wiki/';
		$this->setUpRequestVariablesAndParseRequest(array(
			'PHP_SELF'    => $path . self::SCRIPT_NAME,
			'REQUEST_URI' => $path . self::SCRIPT_NAME
		));

		$this->assertEquals($path, $this->request->getBasePath());
	}

	public function testArgumentsEmpty() {
		$this->setUpRequestVariablesAndParseRequest(array(
			'PHP_SELF'    => self::SCRIPT_NAME,
			'REQUEST_URI' => '/'
		));

		$this->assertEquals(0, count($this->request->getArguments()));
	}

	public function testArgumentsNotEmpty() {
		$this->setUpRequestVariablesAndParseRequest(array(
			'PHP_SELF'    => self::SCRIPT_NAME,
			'REQUEST_URI' => '/wiki/test'
		));

		$arguments = $this->request->getArguments();
		$this->assertEquals('wiki', $arguments[1]);
		$this->assertEquals('test', $arguments[2]);
	}

	private function setUpRequestVariablesAndParseRequest($serverVariables) {
		$serverVariables = array_merge($this->baseServerVariables, $serverVariables);
		$this->request->setServerVariables($serverVariables);
		$this->request->parse();
	}

	public function tearDown() {
		$this->request = null;
	}
}
?>
