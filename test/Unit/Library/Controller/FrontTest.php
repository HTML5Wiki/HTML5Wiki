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
	private $systemBasePath = '';
	private $applicationPath = '';
	private $libraryPath = '';

	public function setUp() {
		$this->config = array(
			'routing' => array(
				'defaultController' => 'unittest',
				'defaultAction'     => 'index'
			),
		);

		$this->systemBasePath = realpath(dirname(__CLASS__));
		$this->applicationPath = $this->systemBasePath . DIRECTORY_SEPARATOR . 'Unit' . DIRECTORY_SEPARATOR . 'Library' . DIRECTORY_SEPARATOR . 'Controller' . DIRECTORY_SEPARATOR . 'FactoryTest' . DIRECTORY_SEPARATOR . 'CorrectControllers' . DIRECTORY_SEPARATOR;
		$this->libraryPath     = $this->systemBasePath . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR;
	}

	/**
	 *
	 * @expectedException Html5Wiki_Exception_InvalidArgument
	 */
	public function testInvalidArguments() {
		$frontController = new Html5Wiki_Controller_Front(new Zend_Config(array()), null, null, null);
	}
	
	public function testGetInstance() {
		$config = new Zend_Config($this->config);
		$request = new Test_Unit_Library_Routing_RequestStub();
		$response = new Test_Unit_Library_Routing_ReponseFake();
		$router = new Html5Wiki_Routing_Router($config, $response, $request);
		
		$frontController = new Html5Wiki_Controller_Front($config, $this->systemBasePath, $this->libraryPath, $this->applicationPath, $router);
		
		$this->assertEquals(Html5Wiki_Controller_Front::getInstance(), $frontController);
		$this->assertInstanceOf('Html5Wiki_Routing_Router', $frontController->getRouter());
		$this->assertInstanceOf('Html5Wiki_Routing_Response', $frontController->getResponse());
		$this->assertInstanceOf('Zend_Config', $frontController->getConfig());
	}

	public function testDispatchAgainstMock() {
		$config = new Zend_Config($this->config);
		
		$request = new Test_Unit_Library_Routing_RequestStub();
		$request->setArguments(array(null, 'unittest'));
		$response = new Test_Unit_Library_Routing_ReponseFake();
		$router = new Html5Wiki_Routing_Router($config, $response, $request);
		
		$frontController = new Html5Wiki_Controller_Front($config, $this->systemBasePath, $this->libraryPath, $this->applicationPath, $router, $response);

		$frontController->run();
		$frontController->render();
		
		$this->assertEquals('test', $response->renderedData);
	}

}

?>
