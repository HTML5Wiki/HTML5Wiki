<?php
/**
 * This file is part of the HTML5Wiki Project.
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright Html5Wiki 2011
 * @package Html5Wiki
 * @subpackage Test
 **/

require '../../library/Html5Wiki/Test/SeleniumTestCase.php';

/**
 * Google Test case (just for testing the selenium test case)
 **/
class GoogleTest extends Html5Wiki_Test_SeleniumTestCase {
	protected function setUp() {
		$this->setBrowserUrl('http://www.google.com');
	}

	public function testGoogle() {
		$this->open('http://www.google.com');
		$this->type('q', 'selenium test');
		$this->click('btnG');
		$this->assertTrue("Expected URL redirect.", strpos($this->getBrowserUrl(), "selenium%20test"));
	}
}

?>