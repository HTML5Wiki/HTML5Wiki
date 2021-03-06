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

require_once 'RequestStub.php';
require_once 'ResponseFake.php';

/**
 * Router test
 */
class Test_Unit_Library_Routing_RouterTest extends PHPUnit_Framework_TestCase {
	
	const DEFAULT_CONTROLLER = 'wiki';
	const DEFAULT_ACTION = 'index';

	/**
	 *
	 * @var Test_Unit_Library_Routing_RequestStub
	 */
	private $request;
	
	/**
	 *
	 * @var Html5Wiki_Routing_Router 
	 */
	private $router;
	
	/**
	 *
	 * @var Test_Unit_Library_Routing_ReponseFake 
	 */
	private $response;
	
	/**
	 *
	 * @var array
	 */
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
	
	public function testGetRequest() {
		$this->assertEquals($this->request, $this->router->getRequest());
	}
	
	public function testSetRequest() {
		$request = new Test_Unit_Library_Routing_RequestStub();
		$request->setArguments(array(null, 'test', 'bar'));
		
		$this->router->setRequest($request);
		
		$this->assertEquals($request, $this->router->getRequest());
		$this->assertNotEquals($request, $this->request);
	}

	public function testDefaultControllerAndAction() {
		$this->router->route();

		$this->assertEquals(self::DEFAULT_CONTROLLER, $this->router->getController());
		$this->assertEquals(self::DEFAULT_ACTION, $this->router->getAction());
	}

	public function testControllerAndAction() {
		$this->request->setArguments(array(null, 'wiki', 'test'));

		$this->router->route();

		$this->assertEquals('wiki', $this->router->getController());
		$this->assertEquals('test', $this->router->getAction());
	}

	/**
	 * @expectedException Html5Wiki_Exception
	 */
	public function testSanitizeController() {
		$this->request->setArguments(array(null, '../wiki'));

		$this->router->route();
	}

	/**
	 * @expectedException Html5Wiki_Exception
	 */
	public function testSanitizeAction() {
		$this->request->setArguments(array(null, 'test', '../wiki'));

		$this->router->route();
	}
	
	public function testRedirect() {
		$this->router->redirect('/wiki/testredirect');
		$this->response->render();
		
		$this->assertEquals("Location: /wiki/testredirect,1,302\n", $this->response->renderedHeader);
	}
	
	public function testBuildUrl() {
		$this->assertEquals('/wiki/testurl', $this->router->buildUrl(array('wiki', 'testurl')));
	}

	public function tearDown() {
		unset($this->request, $this->response, $this->router);
	}
}
?>
