<?php
/**
 * This file is part of the HTML5Wiki Project.
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright Html5Wiki 2011
 * @package Html5Wiki
 * @subpackage Test
 */
require_once 'PHPUnit/Autoload.php';

/**
 * Abstract test case for skipping the test cases if configuration says so.
 */
abstract class Test_Functional_SeleniumTestCase extends PHPUnit_Extensions_SeleniumTestCase {
    public function setUp() {
		parent::setUp();
		
		if (defined('SKIP_FUNCTIONAL_TESTS') && SKIP_FUNCTIONAL_TESTS === true) {
			$this->markTestSkipped('Skipped '. __CLASS__ . ' because of configuration.');
			return false;
		}
	}
}
?>
