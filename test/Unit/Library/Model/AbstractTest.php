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
 * Abstract Test
 */
abstract class Test_Unit_Library_Model_AbstractTest extends PHPUnit_Framework_TestCase {

	/**
	 * Zend Db Adapter Instance
	 * @var Zend_Db_Adapter_Pdo_Mysql
	 */
	protected $db = null;

	/**
	 * @var	Zend_Db_Adapter_Pdo_Mysql
	 */
	protected static $pdo = null;

	/**
	 * @static
	 * @return void
	 */
	public static function setUpBeforeClass() {
		self::$pdo = new Zend_Db_Adapter_Pdo_Mysql(array(
			'host'     => DATABASE_HOST,
			'username' => DATABASE_USERNAME,
			'password' => DATABASE_PASSWORD,
			'dbname'   => DATABASE_SCHEMA
		));
	}

	/**
	 * @return void
	 */
    public function setUp() {
		$this->db = self::$pdo;

	    Zend_Db_Table::setDefaultAdapter($this->db);
	}

	/**
	 * @return void
	 */
	public function tearDown() {
		unset($this->db);
	}

	/**
	 * @static
	 * @return void
	 */
	public static function tearDownAfterClass() {
		foreach(self::$pdo->listTables() as $table) {
			self::$pdo->query('TRUNCATE TABLE ' . $table);
		}

		self::$pdo = null;
	}
}
?>
