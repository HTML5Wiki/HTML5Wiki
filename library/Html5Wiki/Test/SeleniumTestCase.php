<?php
/**
 * This file is part of the HTML5Wiki Project.
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright Html5Wiki 2011
 * @package Html5Wiki
 * @subpackage Library
 */

require_once 'PHPUnit/Autoload.php';

/**
 * Basic Settings for setUp for our selenium test setup
 */
class Html5Wiki_Test_SeleniumTestCase extends PHPUnit_Extensions_SeleniumTestCase {
	protected function setUp() {
		$this->setBrowser("*firefox");
		$this->setHost("selenium.openflex.net");
		$this->setPort(4444);
	}
}
?>
