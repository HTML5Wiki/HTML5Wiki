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
 * JSON Template test
 */
class Test_Unit_Library_Template_JsonTest extends PHPUnit_Framework_TestCase {
	public function testValidOutput() {
		$response = new Test_Unit_Library_Routing_ReponseFake();
		$jsonTemplate = new Html5Wiki_Template_Json($response);
		$jsonTemplate->assign('test', 'ok');
		
		$jsonTemplate->render();
		$response->render();
		
		$this->assertEquals('{"test":"ok"}', $response->renderedData);
		$this->assertEquals("Content-type: text/json,1,0\n", $response->renderedHeader);
	}
}
?>
