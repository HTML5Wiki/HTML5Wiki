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
 * Request stub
 */
class Test_Unit_Library_Routing_RequestStub extends Html5Wiki_Routing_Request {

	/**
	 * Override construct so serverVariables don't get set to $_SERVER
	 */
	public function __construct() {

	}

	/**
	 * Override parse so nothing gets parsed; do it with setters instead
	 */
	public function parse() {
		
	}

}

?>
