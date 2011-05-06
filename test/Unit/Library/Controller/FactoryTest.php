<?php
/**
 * Factory test
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @package Test
 * @subpackage Controller
 */
require_once 'Html5Wiki/Exception.php';
require_once 'Html5Wiki/Exception/404.php';
require_once 'Html5Wiki/Exception/InvalidArgument.php';
require_once 'Html5Wiki/Controller/Factory.php';

class Test_Unit_Library_Controller_FactoryTest extends PHPUnit_Framework_TestCase {

	private $testingBasePath = '';
	private $router;
	
	private $config = array(
		'routing' => array(
			'defaultController' => 'wiki',
			'defaultAction' => 'index'
		)
	);

	public function setUp() {
		$this->testingBasePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'FactoryTest' . DIRECTORY_SEPARATOR;

		$request = new Test_Unit_Routing_RequestStub();
		$this->router  = new Html5Wiki_Routing_Router(new Zend_Config($this->config), $request);
		$this->router->route();
	}

	/**
	 *
	 * @expectedException Html5Wiki_Exception_InvalidArgument
	 */
	public function testInvalidApplicationPath() {
		Html5Wiki_Controller_Factory::factory(null, $this->router);
	}

	/**
	 *
	 * @expectedException Html5Wiki_Exception_404
	 */
	public function testWrongApplicationPath() {
		Html5Wiki_Controller_Factory::factory('.', $this->router);
	}

	/**
	 *
	 * @expectedException Html5Wiki_Exception_404
	 */
	public function testDirectoryWithNoPhpFiles() {
		Html5Wiki_Controller_Factory::factory($this->testingBasePath . 'NoPhpFiles' , $this->router);
	}

	/**
	 *
	 * @expectedException Html5Wiki_Exception_404
	 */
	public function testDirectoryWithWrongControllers() {
		Html5Wiki_Controller_Factory::factory($this->testingBasePath . 'WrongControllers' , $this->router);
	}

	public function testLoadCorrectController() {
		$this->router->route();

		$path = $this->testingBasePath . 'CorrectControllers';
		include_once $path . DIRECTORY_SEPARATOR . 'WikiController.php';

		$controller = Html5Wiki_Controller_Factory::factory($path, $this->router);
		$this->assertTrue($controller instanceof Html5Wiki_Controller_Abstract);
	}

	public function tearDown() {
		$this->applicationPath = null;
		$this->router = null;
	}
}

?>
