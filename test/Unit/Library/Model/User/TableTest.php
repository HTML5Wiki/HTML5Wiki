<?php
/**
 * Created by JetBrains PhpStorm.
 * User: nicolas
 * Date: 23.05.11
 * Time: 15:31
 * To change this template use File | Settings | File Templates.
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
}
