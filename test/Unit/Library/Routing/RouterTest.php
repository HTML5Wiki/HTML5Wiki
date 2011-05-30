<?php

require_once 'RequestStub.php';
require_once 'ResponseFake.php';

/**
 * Router test
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @package Test
 * @subpackage Routing
 */
class Test_Unit_Library_Routing_RouterTest extends PHPUnit_Framework_TestCase {
	
	const DEFAULT_CONTROLLER = 'wiki';
	const DEFAULT_ACTION = 'index';

	private $request;
	private $router;
	private $response;
	private $config = array(
		'routing' => array(
			'defaultController' => self::DEFAULT_CONTROLLER,
			'defaultAction' => self::DEFAULT_ACTION
		)
	);

    public function setUp() {
		$this->request = new Test_Unit_Library_Routing_RequestStub();
		$this->response = new Test_Unit_Library_Routing_ReponseFake();
		$this->router  = new Html5Wiki_Routing_Router(new Zend_Config($this->config), $this->response, $this->request);
	}

	public function testDefaultControllerAndAction() {
		$this->router->route();

		$this->assertEquals(self::DEFAULT_CONTROLLER, $this->router->getController());
		$this->assertEquals(self::DEFAULT_ACTION, $this->router->getAction());
	}

	public function testControllerAndAction() {
		$this->request->setArguments(array (
			1 => 'wiki',
			2 => 'test'
		));

		$this->router->route();

		$this->assertEquals('wiki', $this->router->getController());
		$this->assertEquals('test', $this->router->getAction());
	}

	/**
	 * @expectedException Html5Wiki_Exception
	 */
	public function testSanitizeController() {
		$this->request->setArguments(array(
			1 => '../wiki'
		));

		$this->router->route();
	}

	/**
	 * @expectedException Html5Wiki_Exception
	 */
	public function testSanitizeAction() {
		$this->request->setArguments(array(
			2 => '../wiki'
		));

		$this->router->route();
	}

	public function tearDown() {
		$this->request = null;
		$this->router  = null;
	}
}
?>
