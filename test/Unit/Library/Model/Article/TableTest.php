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

require_once 'library/Zend/Db/Table/Abstract.php';
require_once 'test/Unit/Library/Model/AbstractTest.php';
require_once 'library/Html5Wiki/Model/Article/Table.php';


class Test_Unit_Library_Model_Article_TableTest extends Test_Unit_Library_Model_AbstractTest {
	
	/**
	 * @var Html5Wiki_Model_Media_Table
	 */
	private $table;
	
	/**
	 * 
	 */
	public function setUp() {
		parent::setUp();
		$this->table = new Html5Wiki_Model_Article_Table(array('db' => $this->db));
	}
	/**
	 * 
	 */
	public function testInsert() {
		$time = time();
		$data = array(
			'mediaVersionId'			=> 1,
			'mediaVersionTimestamp'		=> $time,
			'title'						=> 'testarticle',
			'content'					=> 'someContent'
		);
		
		$pk = $this->table->insert($data);

		$this->assertEquals($pk, array('mediaVersionId' => 1, 'mediaVersionTimestamp' => $time));
	}


	/**
	 *
	 */
	public function tearDown() {
		parent::tearDown();

		$this->table->delete('');
	}
}
?>