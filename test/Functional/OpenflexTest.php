<?php
/**
 * This file is part of the HTML5Wiki Project.
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright Html5Wiki 2011
 * @package Html5Wiki
 * @subpackage Test
 */

require 'library/Html5Wiki/Test/SeleniumTestCase.php';

/**
 * Openflex Test case (just for testing the selenium & ci)
 */
class Test_Functional_OpenflexTest extends Html5Wiki_Test_SeleniumTestCase {
	protected function setUp() {
		parent::setUp();
		$this->setBrowserUrl('http://www.openflex.net');
	}

	public function testOpenflex() {
		$this->open('http://www.openflex.net');
		$this->assertTrue(strpos($this->getTitle(), "OpenFlex.net") !== false,
				"Expected 'OpenFlex.net' in title of openflex.net");
	}
}
