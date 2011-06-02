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
 
/**
 * Media version table test
 */
class Test_Unit_Library_Model_MediaVersion_TableTest extends Test_Unit_Library_Model_AbstractTest {

	/**
	 * @var Html5Wiki_Model_MediaVersion_Table
	 */
	protected	$table;

	/**
	 * @var Integer
	 */
	protected static $timestamp;

	public static function setUpBeforeClass() {
		parent::setUpBeforeClass();
		self::$timestamp = time();
	}


	public function setUp() {
		parent::setUp();

		$this->table	= new Html5Wiki_Model_MediaVersion_Table();
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
		$mediaVersion	= $this->table->fetchMediaVersion(1, self::$timestamp);

		$this->assertEquals(1, $mediaVersion->id);
		$this->assertEquals('test/testarticle', $mediaVersion->permalink);

		$mediaVersion	= $this->table->fetchMediaVersion(1, 0);

		$this->assertEquals(1, $mediaVersion->id);
		$this->assertEquals('test/testarticle', $mediaVersion->permalink);
	}
}
