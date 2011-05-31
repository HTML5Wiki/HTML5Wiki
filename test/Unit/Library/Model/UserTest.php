<?php
/**
 * Created by JetBrains PhpStorm.
 * User: nicolas
 * Date: 27.05.11
 * Time: 14:31
 * To change this template use File | Settings | File Templates.
 */

class Test_Unit_Library_Model_UserTest extends Test_Unit_Library_Model_AbstractTest {

	/**
	 * @var Html5Wiki_Model_User_Table
	 */
	protected $table;

	/**
	 * @var Test_Unit_Library_Model_UserFake
	 */
	protected $user;

	/**
	 * @var array
	 */
	protected $userData;

	public function setUp() {
		parent::setUp();

		$this->table	= new Html5Wiki_Model_User_Table();
		// all tests with fake class. Because of error in the saveCookie Method. User class needs to be refactored to the next release
		$this->user		= new Html5Wiki_Model_User();


		$this->userData = array(
			'email'	=> 'test@example.com',
			'name'	=> 'Test User'
		);
	}

	public function testLoadById() {
		$userRow	= $this->table->createRow($this->userData);
		$userRow->save();

		$this->user->loadById(1);

		$this->assertEquals($this->userData['name'], $this->user->name);
		$this->assertEquals($this->userData['email'], $this->user->email);
		$this->assertEquals($this->userData['name'], $this->user->__toString());
	}


	public function testLoadByIdNameAndEmail() {
		$this->assertTrue($this->user->loadByIdNameAndEmail(1, $this->userData['name'], $this->userData['email']));
		$this->assertFalse($this->user->loadByIdNameAndEmail(1, 'Wrong name', 'wrong@email.com'));
	}
}
