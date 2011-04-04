<?php


/**
 * Front controller test
 *
 * @author		Nicolas Karrer <nkarrer@hsr.ch>
 * @copyright	(c) HTML5Wiki Team 2011
 * @package		Test
 * @subpackage	Model
 */

require 'library/Html5Wiki/Db.php';
require 'library/Zend/Db/Table/Abstract.php';
require 'library/Html5Wiki/Model/Article/Table.php';


class Test_Unit_Library_Model_Article_TableTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * @var Html5Wiki_Model_Meida_Table
	 */
	private $table;
	
	/**
	 * 
	 */
	public function setUp() {
		$this->table = new Html5Wiki_Model_Article_Table(Html5Wiki_Db::db());
	}
	
	/**
	 * 
	 */
	public function tearDown() {
		
	}
	
	/**
	 * 
	 */
	public function testInsert() {
		$data = array(
			'mediaVersionId'			=> 1,
			'mediaVersionTimestamp'		=> time(),
			'title'						=> 'testarticle',
			'content'					=> 'someContent'
		);
		
		$this->table->insert($data);
	}
}
?>