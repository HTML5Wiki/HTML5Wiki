<?php
/**
 * Created by JetBrains PhpStorm.
 * User: nicolas
 * Date: 24.05.11
 * Time: 09:21
 * To change this template use File | Settings | File Templates.
 */
 
class Test_Unit_Library_Model_MediaVersionManagerTest extends Test_Unit_Library_Model_AbstractTest {

	/**
	 * @var	Html5Wiki_Model_MediaVersion_Table
	 */
	protected $table = null;

	public function setUp() {
		parent::setUp();

		$this->table	= new Html5Wiki_Model_MediaVersion_Table();
	}

	public function testGroupMediaVersionByTimespan() {
		$manager	= new Html5Wiki_Model_MediaVersionManager();

		$version1	= $this->table->createRow(array('id' => 1, 'timestamp' => time()));
		$version1->save();
		$version2	= $this->table->createRow(array('id' => 1, 'timestamp' => time() - 7 * 24 * 3600));
		$version2->save();

		$grouped	= $manager->groupMediaVersionByTimespan(array($version1, $version2));

		$this->assertArrayHasKey('lastweek', $grouped);
		$this->assertArrayHasKey('today', $grouped);
	}
}
