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
 * user test
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

	public function testLoadByWrongId() {
		$this->assertFalse($this->user->loadById(2));
	}

	public function testLoadByIdNameAndEmail() {
		$this->assertTrue($this->user->loadByIdNameAndEmail(1, $this->userData['name'], $this->userData['email']));
		$this->assertFalse($this->user->loadByIdNameAndEmail(1, 'Wrong name', 'wrong@email.com'));
	}
}
