<?php


include 'db_config.php';

class Html5Wiki_Db {
	
	public static $CONFIG = array();
	
	protected static $db = null;
	
	public static function db() {
		if( self::$db === null ) {
			self::$db = Zend_Db::factory('Pdo_Mysql', self::config());
		} 
		
		return self::$db;
	}
	
	
	protected static function config() {
		return self::$CONFIG['database'];
	}
}
?>