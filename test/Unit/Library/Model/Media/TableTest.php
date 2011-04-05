<?php
/**
 * Front controller test
 *
 * @author		Nicolas Karrer <nkarrer@hsr.ch>
 * @author      Michael Weibel <mweibel@hsr.ch>
 * @copyright	(c) HTML5Wiki Team 2011
 * @package		Test
 * @subpackage	Model
 */

//require 'library/Zend/Db/Table/Abstract.php';
//require 'library/Html5Wiki/Model/Media/Table.php';

class Test_Unit_Library_Model_Media_TableTest extends Test_Unit_Library_Model_AbstractTest {
	
	/**
	 * @var Html5Wiki_Model_Meida_Table
	 */
	private $table;
	
	/**
	 * 
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		$this->table = new Html5Wiki_Model_Media_Table(array('db' => $this->db));
	}
	
	/**
	 * 
	 * @return void
	 */
	public function tearDown() {
		
	}
}
?>