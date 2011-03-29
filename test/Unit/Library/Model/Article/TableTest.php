<?php
/**
 * Front controller test
 *
 * @author		Nicolas Karrer <nkarrer@hsr.ch>
 * @copyright	(c) HTML5Wiki Team 2011
 * @package		Test
 * @subpackage	Model
 */
class Test_Unit_Library_Model_Article_TableTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * @var Html5Wiki_Model_Meida_Table
	 */
	private $table;
	
	/**
	 * 
	 */
	public function setUp() {
		$this->table = new Html5Wiki_Model_Article_Table();
	}
	
	/**
	 * 
	 */
	public function tearDown() {
		
	}
}
?>