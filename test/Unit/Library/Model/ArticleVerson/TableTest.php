<?php
/**
 * This file is part of the HTML5Wiki Project.
 * 
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * http://www.github.com/HTML5Wiki/HTML5Wiki/blob/master/LICENSE
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to mweibel@hsr.ch so we can send you a copy immediately.
 *
 * @author Nicolas Karrer <nkarrer@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @category Html5Wiki 
 * @package Test
 * @subpackage Unit
 */

include 'Unit/Library/Model/MediaVersion/FakeTable.php';

/**
 * Article version table test
 */
class Test_Unit_Library_Model_ArticleVersion_TableTest extends Test_Unit_Library_Model_AbstractTest {

	/**
	 * @var Html5Wiki_Library_Model_ArticleVersion_Table
	 */
	protected $table;
	protected $mediaVersionTable;
	protected static $timestamp;

	public static function setUpBeforeClass() {
		parent::setUpBeforeClass();
		self::$timestamp = time();
	}

	public function setUp() {
		parent::setUp();

		$this->table	= new Html5Wiki_Model_ArticleVersion_Table();
		// instantiate fake table of media version because timestamp is set automatically in the original version.
		$this->mediaVersionTable	= new Test_Unit_Library_Model_MediaVersion_FakeTable();
	}

	/**
	 * @return void
	 */
	public function testSaveArticle() {
		$this->mediaVersionTable->saveMediaVersion(array('id' => 1, 'timestamp' => self::$timestamp));
		$primary = $this->table->saveArticle(array('mediaVersionId' => 1, 'mediaVersionTimestamp' => self::$timestamp, 'title' => 'Test Article'));

		$this->assertEquals($primary['mediaVersionId'], 1);
		$this->assertEquals($primary['mediaVersionTimestamp'], self::$timestamp);
	}

	/**
	 * @return void
	 */
	public function testFetchLatestArticles() {
		$this->mediaVersionTable->saveMediaVersion(array('id' => 1, 'timestamp' => (self::$timestamp + 3600)));
		$this->table->saveArticle(array('mediaVersionId' => 1, 'mediaVersionTimestamp' => (self::$timestamp + 3600), 'title' => 'Test Article', 'content' => '<bold>My New Text</bold>'));

		$this->mediaVersionTable->saveMediaVersion(array('id' => 2, 'timestamp' => (self::$timestamp + 7200)));
		$this->table->saveArticle(array('mediaVersionId' => 2, 'mediaVersionTimestamp' => (self::$timestamp + 7200), 'title' => 'Another Article', 'content' => 'Some Text'));

		$articles = $this->table->fetchLatestArticles();

		$this->assertEquals(2, sizeof($articles));
		$this->assertEquals(2, $articles[0]['mediaVersionId']);
		$this->assertEquals(1, $articles[1]['mediaVersionId']);
	}

	/**
	 * @return void
	 */
	public function testFetchArticlesById() {
		$articles	= $this->table->fetchArticlesById(1);


		$this->assertEquals(2, sizeof($articles));
		$this->assertEquals(1, $articles[0]['mediaVersionId']);
		$this->assertEquals(1, $articles[1]['mediaVersionId']);
		$this->assertEquals(self::$timestamp + 3600, $articles[0]['mediaVersionTimestamp']);
		$this->assertEquals(self::$timestamp, $articles[1]['mediaVersionTimestamp']);
	}

}
