<?php


class Html5Wiki {
	
	
	protected $db = null;
	
	public static function db() {
		if( !( $db instanceof Zend_Db )) {
			$db = Zend_Db::factory(Html5Wiki::config()->database);
		} 
		
		return $db;
	}
	
	
	
	public static function config() {
		if(!( $config instanceof Zend_Config )) {
			$config = new Zend_Config($_CONFIG);
		}
		
		return $config;
	}
}
?>