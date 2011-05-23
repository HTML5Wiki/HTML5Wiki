<?php
/**
 * This file is part of the HTML5Wiki Project.
 *
 * @author Alexandre Joly <ajoly@hsr.ch>
 * @copyright Html5Wiki 2011
 * @package Html5Wiki
 * @subpackage Test
 */
require_once 'PHPUnit/Autoload.php';
require_once 'SeleniumTestCase.php';

/**
 * Index Test case
 */
class Test_Functional_IndexTest extends Test_Functional_SeleniumTestCase {
	public function setUp() {
		parent::setUp();
		$this->setBrowserUrl(TEST_HOST);
	}

	public function testTitle() {
		$this->open(TEST_HOST);
		$this->assertTitle('HTML5Wiki | HTML5Wiki');
	}
	
	public function testLogo() {
		$this->open(TEST_HOST);
		$this->assertElementPresent('css=.logo');
	}
	
	public function testMainMenu() {
		$this->open(TEST_HOST);
		$this->assertElementPresent('css=.main-menu');
		$this->assertElementPresent('css=.main-menu li.home');
		$this->assertElementPresent('css=.main-menu li.updates');
	}
	
	public function testSearchField() {
		$this->open(TEST_HOST);
		$this->assertElementPresent('css=.main-menu');
		$this->assertElementPresent('css=.main-menu li.search');
	}
}
