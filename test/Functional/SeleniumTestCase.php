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
