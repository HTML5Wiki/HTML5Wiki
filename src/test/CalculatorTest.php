<?php
require_once 'PHPUnit/Framework.php';
include '../Calculator.php';

class CalculatorTest extends PHPUnit_Framework_Testcase {
	public function testAdd() {
		$this->assertEquals(3, Calculator::Add(1,2));
	}
}
