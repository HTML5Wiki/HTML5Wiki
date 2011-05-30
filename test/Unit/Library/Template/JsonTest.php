<?php
/**
 * JSON Template test
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @package Test
 * @subpackage Template
 */

class Test_Unit_Library_Template_JsonTest extends PHPUnit_Framework_TestCase {
	public function testValidOutput() {
		$response = new Test_Unit_Library_Routing_ReponseFake();
		$jsonTemplate = new Html5Wiki_Template_Json($response);
		$jsonTemplate->assign('test', 'ok');
		
		$jsonTemplate->render();
		$response->render();
		
		$this->assertEquals('{"test":"ok"}', $response->renderedData);
		$this->assertEquals("Content-type: text/json\n", $response->renderedHeader);
	}
}
?>
