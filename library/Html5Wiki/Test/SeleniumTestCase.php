<?php

require_once 'PHPUnit/Autoload.php';

/**
 * Basic class to setup our selenium test installation
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @package Library
 * @subpackage Test
 */
class Html5Wiki_Test_SeleniumTestCase extends PHPUnit_Extensions_SeleniumTestCase {
	protected function setUp() {
		$this->setBrowser("*firefox");
		$this->setHost("selenium.openflex.net");
		$this->setPort(4444);
	}
}