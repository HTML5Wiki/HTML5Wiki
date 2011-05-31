<?php
/**
 * Abstract Test
 *
 * @author		Michael Weibel <mweibel@hsr.ch>
 * @copyright	(c) HTML5Wiki Team 2011
 * @package		Test
 * @subpackage  Model
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
