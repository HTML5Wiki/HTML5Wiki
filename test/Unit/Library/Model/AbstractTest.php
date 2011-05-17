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

    public function setUp() {
		$this->db = new Zend_Db_Adapter_Pdo_Mysql(array(
			'host'     => DATABASE_HOST,
			'username' => DATABASE_USERNAME,
			'password' => DATABASE_PASSWORD,
			'dbname'   => DATABASE_SCHEMA
		));

	}

	public function tearDown() {
		$this->db = null;
	}
}
?>
