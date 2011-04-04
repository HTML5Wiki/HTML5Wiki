<?php

require_once 'library/Html5Wiki/Routing/Interface/Request.php';
require_once 'library/Html5Wiki/Routing/Request.php';

/**
 * Request stub
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @package Test
 * @subpackage Routing
 */
class Test_Unit_Routing_RequestStub extends Html5Wiki_Routing_Request {

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
