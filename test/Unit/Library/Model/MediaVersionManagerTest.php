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

		// create fake table because of the original saves own timestamps.
		$this->table	= new Html5Wiki_Model_MediaVersion_Table();
	}

	public function testGetMediaVersionByPermalink() {
		$testData	= $this->createTestMediaVersions();

		$manager	= new Html5Wiki_Model_MediaVersionManager();

		$versions	= $manager->getMediaVersionsByPermalink('test');

		$this->assertEquals(2, sizeof($versions));
		$this->assertEquals(1, $versions[0]->id);

		return $testData;

	}

	/**
	 * @depends		testGetMediaVersionByPermalink
	 */
	public function testGroupMediaVersionByTimespan(array $testData) {
		$manager	= new Html5Wiki_Model_MediaVersionManager();

		$grouped	= $manager->groupMediaVersionByTimespan($testData);

		$this->assertArrayHasKey('lastweek', $grouped);
		$this->assertArrayHasKey('daybeforeyesterday', $grouped);
		$this->assertArrayHasKey('today', $grouped);

		return $testData;
	}

	/**
	 * @return		void
	 */
	protected function createTestMediaVersions() {
		$testData = array();

		$version = $this->table->createRow(array('id' => 1, 'timestamp' => time(), 'permalink' => 'test'));
		$version->save();
		array_push($testData, $version);
		$version = $this->table->createRow(array('id' => 1, 'timestamp' => (time() - 7 * 24 * 3600), 'permalink' => 'test'));
		$version->save();
		array_push($testData, $version);
		$version = $this->table->createRow(array('id' => 2, 'timestamp' => time() - 2 * 24 * 3600, 'permalink' => 'test/anothertest'));
		$version->save();
		array_push($testData, $version);

		return $testData;
	}
}
