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
	 * @param	Integer	$idUser
	 */
	public function __construct($idUser = 0) {
		$idUser = intval($idUser);

		$this->dbAdapter = new Html5Wiki_Model_User_Table();

		$this->data['id'] = $idUser;
		
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
	
	/**
	 * Loads User by existing cookie
	 */
	private function loadFromCookie() {
		if( isset($_COOKIE['currentUserId']) ) {
			$this->load($_COOKIE['currentUserId']);
		 }
	}
	
	/**
	 * Set user data
	 *
	 * @param $data
	 */
	public function setData(array $data) {
		$this->data = $data;
	}
	
	/**
	 * Save user
	 *
	 * If id given update the user with given data
	 * If users email & name are already in the database load the user (cookie not set & user exists)
	 *
	 *
	 * @param	Array	$saveData
	 */
	public function save() {
		if( $this->data['id'] == 0) {
			if($userData = $this->dbAdapter->userExists($this->data['name'], $this->data['email'])) {
				$this->data = $userData->toArray();
			} else {
				$this->data['id'] = $this->dbAdapter->saveNewUser($this->data);
			}
		} else {
			$this->dbAdapter->updateUser($this->data['id'], $this->data);
		}

		setcookie('currentUserId', $this->data['id'], time() + 3600, '/', null, false, true);
	}


	/**
	 * @return 
	 */
	public function toString() {
		return $this->name;
	}
}
?>