<?php
/**
 * This file is part of the HTML5Wiki Project.
 *
 * @author		Nicolas Karrer <nkarrer@hsr.ch>
 * @copyright	(c) HTML5Wiki Team 2011
 * @package		Html5Wiki
 * @subpackage	Library
 */

// @todo move this to a better place
include 'db_config.php';

/**
 * Handles the database
 * 
 * @author	Nicolas Karrer <nkarrer@hsr.ch>
 */
class Html5Wiki_Db {
	
	/**
	 * Db config
	 * 
	 * @var array
	 */
	public static $CONFIG = array();
	
	/**
	 * Instance of the database connector
	 * 
	 * @var Zend_Db
	 */
	protected static $db = null;
	
	/**
	 * Returns an instance of the database
	 * 
	 * @return Zend_Db
	 */
	public static function db() {
		if( self::$db === null ) {
			self::$db = Zend_Db::factory('Pdo_Mysql', self::config());
		} 
		
		return self::$db;
	}
	
	/**
	 * Returns the db config array
	 * 
	 * @return array
	 */
	private static function config() {
		return self::$CONFIG['database'];
	}
}
?>