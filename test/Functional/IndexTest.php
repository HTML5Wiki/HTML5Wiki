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
 * @author Alexandre Joly <ajoly@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @category Html5Wiki 
 * @package Test
 * @subpackage Functional
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
