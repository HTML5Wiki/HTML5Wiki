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

/**
 * Index Test case
 */
class Test_Functional_IndexTest extends PHPUnit_Extensions_SeleniumTestCase {
	
	const MAIN_URL = 'http://vs01.openflex.net';

	protected function setUp() {
		parent::setUp();
		$this->setBrowserUrl(self::MAIN_URL);
	}

	public function testTitle() {
		$this->open(self::MAIN_URL);
		$this->assertTitle('Hello World | HTML5Wiki');
	}
	
	public function testLogo() {
		$this->open(self::MAIN_URL);
		$this->assertElementPresent('css=.logo');
	}
	
	public function testMainMenu() {
		$this->open(self::MAIN_URL);
		$this->assertElementPresent('css=.main-menu');
		$this->assertElementPresent('css=.main-menu li.home');
		$this->assertElementPresent('css=.main-menu li.updates');
	}
	
	public function testSearchField() {
		$this->open(self::MAIN_URL);
		$this->assertElementPresent('css=.main-menu');
		$this->assertElementPresent('css=.main-menu li.search');
	}
}
