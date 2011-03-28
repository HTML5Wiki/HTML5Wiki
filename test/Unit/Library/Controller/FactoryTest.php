<?php
/**
 * Factory test
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @package Test
 * @subpackage Controller
 */
class Test_Unit_Library_Controller_FactoryTest extends PHPUnit_Framework_TestCase {

	private $applicationPath = '';

	public function setUp() {
		$this->applicationPath = realpath(dirname(__FILE__) . '/../../../../');
	}

	public function tearDown() {
		
	}
}

?>
