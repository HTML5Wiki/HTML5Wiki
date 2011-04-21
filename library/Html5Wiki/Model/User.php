<?php

/**
 * This file is part of the HTML5Wiki Project.
 *
 * @author		Nicolas Karrer <nkarrer@hsr.ch>
 * @copyright	(c) HTML5Wiki Team 2011
 * @package		Html5Wiki
 * @subpackage	Library
 */

/**
 * 
 * @author Nicolas Karrer <nkarrer@hsr.ch>
 *
 */
class Html5Wiki_Model_User extends Html5Wiki_Model_Abstract {
	
	/**
	 * 
	 * @var Zend_Db_Table_Abstract
	 */
	protected $dbAdapter;
	
	/**
	 * 
	 * @param	Integer	$idUser
	 */
	public function __construct($idUser = 0) {
		$idUser = intval($idUser);
		
		$this->dbAdabter = new Html5Wiki_Model_User_Table();
		
		if( $idUser > 0 ) {
			$this->load($idUser);
		} else {
			$this->loadFromCookie();
		}
	}
	
	/**
	 * Loads User by its id
	 */
	private function load($idUser) {	
		$this->data = $this->dbAdapter->fetchUser($idUser)->toArray();
	}
	
	
	private function loadFromCookie() {
		 if( isset($_COOKIE['currentUserId']) ) {
			$this->load($_SESSION['currentUserId']);
		 }
	}
	
	/**
	 * 
	 * @param $data
	 */
	public function setData(array $data) {
		$this->data = $data;
	}
	
	/**
	 * 
	 * @param	Array	$saveData
	 */
	public function save() {
		if( $this->id == 0 ) {
			$this->id = $this->dbAdapter->saveNewUser($this->data);
		} else {
			$this->dbAdabter->updateUser($this->id, $this->data);
		}
	}
}
?>