<?php
/**
 * This file is part of the html5wiki package.
 *
 * @copyright 2011 HTML5Wiki Team
 * @package Html5Wiki
 */

/**
 * Html5wiki basic testsuite
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 */
class Html5Wiki_Test_TestSuite extends PHPUnit_Framework_TestSuite {
	/**
	 * Static function for creating a suite.
	 */
	public static function suite() {
		return new Html5WikiSuite("HTML5WikiTest");
	}
}
