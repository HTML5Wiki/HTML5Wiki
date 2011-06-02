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
 * Media version manager test
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
