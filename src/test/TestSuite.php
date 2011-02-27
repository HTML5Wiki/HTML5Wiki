<?php
/**
 * This file is part of the html5wiki package.
 *
 * @copyright 2011 HTML5Wiki Team
 */

class Html5Wiki_Test_TestSuite extends PHPUnit_Framework_TestSuite {
	public static function suite() {
		return new Html5WikiSuite("HTML5WikiTest");
	}
}
