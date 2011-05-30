<?php
/**
 * Created by JetBrains PhpStorm.
 * User: nicolas
 * Date: 17.05.11
 * Time: 13:46
 * To change this template use File | Settings | File Templates.
 */

require_once 'PHPUnit/Autoload.php';

class Test_Unit_Library_Model_ArticleVersionTest extends Test_Unit_Library_Model_AbstractTest {

	protected $articleData1 = array();

	protected $mediaVersionData1 = array();

	/**
	 * @var Html5Wiki_Model_ArticleVersion_Table
	 */
	protected $tableArticle;

	/**
	 * @var Html5Wiki_Model_MediaVersion_Table
	 */
	protected $tableMediaVersion;

	/**
	 * @var Integer
	 */
	protected static $timestamp;

	/**
	 * @return void
	 */
	public static function setUpBeforeClass() {
		parent::setUpBeforeClass();

		self::$timestamp = time();
	}

	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		//create test article
		$this->tableArticle    = new Html5Wiki_Model_ArticleVersion_Table();
		$this->tableMediaVersion    = new Html5Wiki_Model_MediaVersion_Table();

		$this->articleData1 = array(
			'mediaVersionTimestamp' => self::$timestamp,
			'title'                 => 'Test Article',
			'content'               => 'Test Content with <bold>Bold Text</bold>',
		);

		$this->mediaVersionData1 = array(
			'permalink'     => 'test/testarticle',
			'userId'        => 1,
			'timestamp'     => self::$timestamp
		);
	}

	/**
	 * @return void
	 */
	public function testCreateArticle() {
		$mediaRow   = $this->tableMediaVersion->createRow($this->mediaVersionData1);
		$mediaRow->save();

		$this->articleData1['mediaVersionId'] = $mediaRow->id;

		$articleRow = $this->tableArticle->createRow();
		$articleRow->setFromArray($this->articleData1);
		$articleRow->save();

		$newArticle = new Html5Wiki_Model_ArticleVersion();
		$newArticle->loadLatestByPermalink('test/testarticle');

		$this->assertEquals($this->articleData1['title'], $newArticle->title);
		$this->assertEquals($this->articleData1['content'], $newArticle->content);
		$this->assertEquals($this->mediaVersionData1['permalink'], $newArticle->permalink);
		$this->assertEquals($this->articleData1['mediaVersionId'], $newArticle->id);
		$this->assertEquals($this->articleData1['mediaVersionTimestamp'], $newArticle->timestamp);
	}

	/**
	 * @return void
	 */
	public function testLoadLatestById() {
		$article    = new Html5Wiki_Model_ArticleVersion();
		$article->loadLatestById(1);

		$this->assertEquals($this->articleData1['title'], $article->title);
		$this->assertEquals($this->articleData1['content'], $article->content);
		$this->assertEquals($this->mediaVersionData1['permalink'], $article->permalink);
		$this->assertEquals(1, $article->id);
		$this->assertInternalType('integer', $article->timestamp);
		$this->assertEquals($this->articleData1['mediaVersionTimestamp'], $article->timestamp);
	}

	/**
	 * @return void
	 */
	public function testLoadByIdTimestamp() {
		$article    = new Html5Wiki_Model_ArticleVersion();
		$article->loadByIdAndTimestamp(1, $this->articleData1['mediaVersionTimestamp']);

		$this->assertEquals($this->articleData1['title'], $article->title);
		$this->assertEquals($this->articleData1['content'], $article->content);
		$this->assertEquals($this->mediaVersionData1['permalink'], $article->permalink);
		$this->assertEquals(1, $article->id);
		$this->assertInternalType('integer', $article->timestamp);
		$this->assertEquals($this->articleData1['mediaVersionTimestamp'], $article->timestamp);
	}

	/**
	 * @return void
	 */
	public function testGetCommonName() {
		$article    = new Html5Wiki_Model_ArticleVersion();
		$article->loadLatestById(1);

		$this->assertEquals($this->articleData1['title'], $article->getCommonName());
	}


	public function testGetMediaVersionTimestmap() {
		$article	= new Html5Wiki_Model_ArticleVersion();
		$article->loadLatestById(1);

		$this->assertEquals($this->articleData1['mediaVersionTimestamp'], $article->getMediaVersionTimestamp());
	}

}
