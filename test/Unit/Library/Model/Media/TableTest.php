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

require_once 'Zend/Db/Table/Abstract.php';
require_once 'Html5Wiki/Model/Media/Table.php';

class Test_Unit_Library_Model_Media_TableTest extends Test_Unit_Library_Model_AbstractTest {
	
	/**
	 * @var Html5Wiki_Model_Media_Table
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
		parent::tearDown();
	}
}
?>