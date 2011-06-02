<?php
/**
 * This file is part of the HTML5Wiki Project.
 * 
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * http://www.github.com/HTML5Wiki/HTML5Wiki/blob/master/LICENSE
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to mweibel@hsr.ch so we can send you a copy immediately.
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @category Html5Wiki 
 * @package Test
 * @subpackage Unit
 */

/**
 * Factory test
 */
class Test_Unit_Library_Controller_FactoryTest extends PHPUnit_Framework_TestCase {

	private $testingBasePath = '';
	private $router;
	private $response;
	
	private $config = array(
		'routing' => array(
			'defaultController' => 'unittest',
			'defaultAction' => 'index'
		)
	);

	public function setUp() {
		$this->testingBasePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'FactoryTest' . DIRECTORY_SEPARATOR;

		$request = new Test_Unit_Library_Routing_RequestStub();
		$this->response = new Test_Unit_Library_Routing_ReponseFake();
		$this->router  = new Html5Wiki_Routing_Router(new Zend_Config($this->config), $this->response, $request);
		$this->router->route();
	}

	/**
	 *
	 * @expectedException Html5Wiki_Exception_InvalidArgument
	 */
	public function testInvalidApplicationPath() {
		Html5Wiki_Controller_Factory::factory(null, $this->router, $this->response);
	}

	/**
	 *
	 * @expectedException Html5Wiki_Exception_404
	 */
	public function testWrongApplicationPath() {
		Html5Wiki_Controller_Factory::factory('.', $this->router, $this->response);
	}

	/**
	 *
	 * @expectedException Html5Wiki_Exception_404
	 */
	public function testDirectoryWithNoPhpFiles() {
		Html5Wiki_Controller_Factory::factory($this->testingBasePath . 'NoPhpFiles' , $this->router, $this->response);
	}

	/**
	 *
	 * @expectedException Html5Wiki_Exception_404
	 */
	public function testDirectoryWithWrongControllers() {
		Html5Wiki_Controller_Factory::factory($this->testingBasePath . 'WrongControllers' , $this->router, $this->response);
	}

	public function testLoadCorrectController() {
		$this->router->route();

		$path = $this->testingBasePath . 'CorrectControllers/';

		$controller = Html5Wiki_Controller_Factory::factory($path, $this->router, $this->response);
		$this->assertTrue($controller instanceof Html5Wiki_Controller_Abstract);
	}

	public function tearDown() {
		unset($this->applicationPath, $this->router, $this->response);
	}
}

?>
