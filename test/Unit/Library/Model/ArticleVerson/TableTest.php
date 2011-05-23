<?php
/**
 * Created by JetBrains PhpStorm.
 * User: nicolas
 * Date: 23.05.11
 * Time: 12:36
 * To change this template use File | Settings | File Templates.
 */

include 'Unit/Library/Model/MediaVersion/FakeTable.php';

class Test_Unit_Library_Model_ArticleVersion_TableTest extends Test_Unit_Library_Model_AbstractTest {

	/**
	 * @var Html5Wiki_Library_Model_ArticleVersion_Table
	 */
	protected $table;
	protected $mediaVersionTable;


	public function setUp() {
		parent::setUp();

		$this->table	= new Html5Wiki_Model_ArticleVersion_Table();
		// instantiate fake table of media version because timestamp is set automatically in the original version.
		$this->mediaVersionTable	= new Test_Unit_Library_Model_MediaVersion_FakeTable();

		$this->timestamp = time();
	}

	/**
	 * @return void
	 */
	public function testSaveArticle() {
		$this->mediaVersionTable->saveMediaVersion(array('id' => 1, 'timestamp' => $this->timestamp));
		$primary = $this->table->saveArticle(array('mediaVersionId' => 1, 'mediaVersionTimestamp' => $this->timestamp, 'title' => 'Test Article'));

		$this->assertEquals($primary['mediaVersionId'], 1);
		$this->assertEquals($primary['mediaVersionTimestamp'], $this->timestamp);
	}

	/**
	 * @return void
	 */
	public function testFetchLatestArticles() {
		$this->mediaVersionTable->saveMediaVersion(array('id' => 1, 'timestamp' => ($this->timestamp + 3600)));
		$this->table->saveArticle(array('mediaVersionId' => 1, 'mediaVersionTimestamp' => ($this->timestamp + 3600), 'title' => 'Test Article', 'content' => '<bold>My New Text</bold>'));

		$this->mediaVersionTable->saveMediaVersion(array('id' => 2, 'timestamp' => ($this->timestamp + 7200)));
		$this->table->saveArticle(array('mediaVersionId' => 2, 'mediaVersionTimestamp' => ($this->timestamp + 7200), 'title' => 'Another Article', 'content' => 'Some Text'));

		$articles = $this->table->fetchLatestArticles();

		$this->assertEquals(2, sizeof($articles));
		$this->assertEquals(2, $articles[0]['mediaVersionId']);
		$this->assertEquals(1, $articles[1]['mediaVersionId']);
	}


	public function testFetchArticlesById() {
		$articles	= $this->table->fetchArticlesById(1);


		$this->assertEquals(2, sizeof($articles));
		$this->assertEquals(1, $articles[0]['mediaVersionId']);
		$this->assertEquals(1, $articles[1]['mediaVersionId']);
		$this->assertEquals($this->timestamp + 3600, $articles[0]['mediaVersionTimestamp']);
		$this->assertEquals($this->timestamp, $articles[1]['mediaVersionTimestamp']);
	}

}
