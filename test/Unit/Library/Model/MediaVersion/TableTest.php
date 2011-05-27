<?php
/**
 * Created by JetBrains PhpStorm.
 * User: nicolas
 * Date: 23.05.11
 * Time: 13:27
 * To change this template use File | Settings | File Templates.
 */
 
class Test_Unit_Library_Model_MediaVersion_TableTest extends Test_Unit_Library_Model_AbstractTest {

	/**
	 * @var Html5Wiki_Model_MediaVersion_Table
	 */
	protected	$table;

	/**
	 * @var Integer
	 */
	protected	$timestamp;

	public function setUp() {
		parent::setUp();

		$this->table	= new Html5Wiki_Model_MediaVersion_Table();

		$this->timestamp = time();
	}

	/**
	 * @return void
	 */
	public function testSaveMediaVersion() {
		$primary = $this->table->saveMediaVersion(array('id' => 1, 'permalink' => 'test/testarticle'));

		$this->assertEquals(1, $primary['id']);
		$this->assertInternalType('integer', $primary['timestamp']);
	}

	/**
	 * @return void
	 */
	public function testFechtchMediaByPermaLink() {
		$article	= $this->table->fetchMediaByPermaLink('test/testarticle');

		$this->assertEquals(1, $article['id']);
	}

	public function testFetchMediaVersion() {
		$mediaVersion	= $this->table->fetchMediaVersion(1, $this->timestamp);

		$this->assertEquals(1, $mediaVersion->id);
		$this->assertEquals('test/testarticle', $mediaVersion->permalink);
	}
}
