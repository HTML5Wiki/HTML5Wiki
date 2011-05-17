<?php
/**
 * Front controller test
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @package Test
 * @subpackage Controller
 */

class Test_Unit_Library_Controller_FrontTest extends PHPUnit_Framework_TestCase {

	private $config = array();
	private $basePath = '';
	private $applicationPath = '';
	private $libraryPath = '';

	public function setUp() {
		$this->config = array(
			'routing' => array(
				'defaultController' => 'index',
				'defaultAction'     => 'index'
			),
		);

		$this->basePath = realpath(dirname(__CLASS__) . '/../');
		$this->applicationPath = $this->basePath . DIRECTORY_SEPARATOR . 'test/Unit/Library/Controller/FactoryTest/CorrectControllers' . DIRECTORY_SEPARATOR;
		$this->libraryPath     = $this->basePath . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR;
	}

	/**
	 *
	 * @expectedException Html5Wiki_Exception_InvalidArgument
	 */
	public function testInvalidArguments() {
		$frontController = new Html5Wiki_Controller_Front(new Zend_Config(array()), null, null, null);
	}

	public function testDispatchAgainstMock() {
		$config = new Zend_Config($this->config);
		
		$request = new Test_Unit_Routing_RequestStub();
		$router = new Html5Wiki_Routing_Router($config, $request);

		$frontController = $this->getMock('Html5Wiki_Controller_Front', array('getController', 'dispatch', 'render'), 
								array($config, $this->basePath, $this->libraryPath, $this->applicationPath, $router));
		$frontController->expects($this->once())
						->method('getController')
						->will($this->returnValue(new Application_WikiController()));
		$frontController->expects($this->once())
						->method('dispatch');
		$frontController->expects($this->once())
						->method('render');

		$frontController->run();
	}

	public function tearDown() {
		
	}
}

?>
