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
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @category Html5Wiki 
 * @package Test
 * @subpackage Functional
 */

require_once 'PHPUnit/Autoload.php';

/**
 * Abstract test case for skipping the test cases if configuration says so.
 */
abstract class Test_Functional_SeleniumTestCase extends PHPUnit_Extensions_SeleniumTestCase {

	private $config = array();

	public function setUp() {
		parent::setUp();

		$this->fetchLanguageFile();

		if (defined('SKIP_FUNCTIONAL_TESTS') && SKIP_FUNCTIONAL_TESTS === true) {
			$this->markTestSkipped('Skipped ' . __CLASS__ . ' because of configuration.');
			return false;
		}
	}
	
	private function fetchLanguageFile() {
		$languageFile = realpath(dirname(__FILE__). DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' 
							. DIRECTORY_SEPARATOR . 'languages') . DIRECTORY_SEPARATOR . 'en.php';
		
		ob_start();
		$this->config = include($languageFile);
		ob_end_clean();
	}
	
	protected function getLanguageKey($key) {
		return isset($this->config[$key]) ? $this->config[$key] : "???";
	}

	protected function waitForAjax() {
		$this->waitForCondition("selenium.browserbot.getCurrentWindow().$ !== undefined && selenium.browserbot.getCurrentWindow().$.active == 0;",
				10000);
	}

}

?>
