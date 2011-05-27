<?php
/**
 * Created by JetBrains PhpStorm.
 * User: nicolas
 * Date: 20.05.11
 * Time: 13:58
 * To change this template use File | Settings | File Templates.
 */
 
class Test_Unit_Library_Model_MediaVersionTest extends Test_Unit_Library_Model_AbstractTest {

	protected	$tabelMediaVersion;
	protected	$mediaVersionData	= array();
	protected	$userData			= array();


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

		$this->userData	= array(
			'email'	=> 'test@example.com',
			'name'	=> 'Test User',
		);
	}

	/**
	 * @return void
	 */
	public function testCreateMediaVersionAndLoadById() {
		$userRow	= new Html5Wiki_Model_User_Table();
		$userRow->saveNewUser($this->userData);

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

	public function testLoadByPermalinkAndTimestamp() {
		$mediaVersion	= new Html5Wiki_Model_MediaVersion();
		$mediaVersion->loadByPermalinkAndTimestamp('test/testarticle', $this->mediaVersionData['timestamp']);

		$this->assertEquals('test/testarticle', $mediaVersion->permalink);
		$this->assertEquals(1, $mediaVersion->id);
		$this->assertEquals($this->mediaVersionData['timestamp'], $mediaVersion->timestamp);
		$this->assertEquals(1, $mediaVersion->userId);
		$this->assertEquals('PUBLISHED', $mediaVersion->state);
		$this->assertEquals('New Version', $mediaVersion->versionComment);
		$this->assertEquals('ARTICLE', $mediaVersion->mediaVersionType);
	}

	public function testMemberMethods() {
		$mediaVersion	= new Html5Wiki_Model_MediaVersion();
		$mediaVersion->loadById(1);

		$this->assertEquals('test/testarticle', $mediaVersion->__toString());
		$this->assertEquals($this->mediaVersionData['timestamp'], $mediaVersion->getTimestamp());
		$this->assertEquals('', $mediaVersion->getCommonName());
	}

	public function testGetUser() {
		$mediaVersion	= new Html5Wiki_Model_MediaVersion();
		$mediaVersion->loadById(1);

		$user	= $mediaVersion->getUser();

		$this->assertInstanceOf('Html5Wiki_Model_User', $user);
		$this->assertEquals('test@example.com', $user->email);
		$this->assertEquals('Test User', $user->name);
	}
}
