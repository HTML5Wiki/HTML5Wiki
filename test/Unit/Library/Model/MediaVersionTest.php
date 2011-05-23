<?php
/**
 * Created by JetBrains PhpStorm.
 * User: nicolas
 * Date: 20.05.11
 * Time: 13:58
 * To change this template use File | Settings | File Templates.
 */
 
class Test_Unit_Library_Model_MediaVersionTest extends Test_Unit_Library_Model_AbstractTest {

	protected	$mediaVersionData;
	protected	$tabelMediaVersion;


	public function setUp() {
		parent::setUp();

		$this->tableMediaVersion    = new Html5Wiki_Model_MediaVersion_Table();

		$timestamp      = time();

		$this->mediaVersionData = array(
			'permalink'		=> 'test/testarticle',
			'userId'		=> 1,
			'timestamp'		=> $timestamp,
			'versionComment'=> 'New Version'
		);
	}

	/**
	 * @return void
	 */
	public function testCreateMediaVersionAndLoadById() {
		$mediaRow	= $this->tableMediaVersion->createRow($this->mediaVersionData);
		$mediaRow->save();

		$mediaVersion	= new Html5Wiki_Model_MediaVersion();
		$mediaVersion->loadById(1);

		$this->assertEquals('test/testarticle', $mediaVersion->permalink);
		$this->assertEquals(1, $mediaVersion->id);
		$this->assertEquals($this->mediaVersionData['timestamp'], $mediaVersion->timestamp);
		$this->assertEquals(1, $mediaVersion->userId);
		$this->assertEquals('PUBLISHED', $mediaVersion->state);
		$this->assertEquals('New Version', $mediaVersion->versionComment);
		$this->assertEquals('ARTICLE', $mediaVersion->mediaVersionType);
	}

	/**
	 * @return void
	 */
	public function testLoadLatestByPermalink() {
		$mediaVersion	= new Html5Wiki_Model_MediaVersion();
		$mediaVersion->loadLatestByPermalink('test/testarticle');

		$this->assertEquals('test/testarticle', $mediaVersion->permalink);
		$this->assertEquals(1, $mediaVersion->id);
		$this->assertEquals($this->mediaVersionData['timestamp'], $mediaVersion->timestamp);
		$this->assertEquals(1, $mediaVersion->userId);
		$this->assertEquals('PUBLISHED', $mediaVersion->state);
		$this->assertEquals('New Version', $mediaVersion->versionComment);
		$this->assertEquals('ARTICLE', $mediaVersion->mediaVersionType);
	}

	/**
	 * @return void
	 */
	public function testLoadByIdAndTimestamp() {
		$mediaVersion	= new Html5Wiki_Model_MediaVersion();
		$mediaVersion->loadByIdAndTimestamp(1, $this->mediaVersionData['timestamp']);
		
		$this->assertEquals('test/testarticle', $mediaVersion->permalink);
		$this->assertEquals(1, $mediaVersion->id);
		$this->assertEquals($this->mediaVersionData['timestamp'], $mediaVersion->timestamp);
		$this->assertEquals(1, $mediaVersion->userId);
		$this->assertEquals('PUBLISHED', $mediaVersion->state);
		$this->assertEquals('New Version', $mediaVersion->versionComment);
		$this->assertEquals('ARTICLE', $mediaVersion->mediaVersionType);
	}
}
