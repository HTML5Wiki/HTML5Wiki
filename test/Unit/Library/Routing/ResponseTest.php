<?php

require_once 'ResponseFake.php';

/**
 * Response test
 * 
 * Tests only cookies because other functions are being tested in RouterTest etc.
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @package Test
 * @subpackage Routing
 */
class Test_Unit_Library_Routing_ResponseTest extends PHPUnit_Framework_TestCase {
	public function testCookie() {
		$response = new Test_Unit_Library_Routing_ReponseFake();
		$response->pushCookie('test', 'icantrackyou');
		
		$response->render();
		
		$this->assertEquals("test,icantrackyou,0,,,,\n", $response->renderedCookie);
	}
}
?>
