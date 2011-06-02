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
 * user table model test
 */
class Test_Unit_Library_Model_User_TableTest extends Test_Unit_Library_Model_AbstractTest {

	/**
	 * @var	Html5Wiki_Model_User_Table
	 */
	protected $table;

	public function setUp() {
		parent::setUp();

		$this->table	= new Html5Wiki_Model_User_Table();
	}


	public function testSaveNewUser() {
		$primary	= $this->table->saveNewUser(array('email' => 'test@test.com', 'name' => 'Test User'));

		$this->assertEquals(1, $primary['id']);
	}


	public function testFetchUser() {
		$user	= $this->table->fetchUser(1);

		$this->assertEquals(1, $user['id']);
		$this->assertEquals('test@test.com', $user['email']);
		$this->assertEquals('Test User', $user['name']);
	}


	public function testUserExists() {
		$this->assertInstanceOf('Html5Wiki_Model_User', $this->table->userExists('Test User', 'test@test.com'));
		$this->assertNull($this->table->userExists('No User', 'notest@test.com'));
	}

	public function testUpdateUser() {
		$this->table->updateUser(1, array('email' => 'test@test.ch', 'name' => 'Test Benutzer'));

		$user	= $this->table->fetchUser(1);

		$this->assertEquals('test@test.ch', $user['email']);
		$this->assertEquals('Test Benutzer', $user['name']);
	}
}
