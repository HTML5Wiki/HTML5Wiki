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
class Html5Wiki_Model_User extends Zend_Db_Table_Row_Abstract {
	
	protected $_tableClass = 'Html5Wiki_Model_User_Table';
	
	/**
	 * Loads User by its id
	 */
	public function loadById($userId) {
		$where = $this->select()->where('id = ?', $userId);
		$row = $this->_getTable()->fetchRow($where);
		
		if (isset($row->id)) {
			$this->_data = $row->toArray();
			$this->_cleanData = $this->_data;
			$this->_modifiedFields = array();
			return true;
		}
		return false;
	}
	
	public function loadByIdNameAndEmail($id, $name, $email) {
		$where = $this->select()->where('id = ?', $id)
					->where('name = ?', $name)
					->where('email = ?', $email);
		$row = $this->_getTable()->fetchRow($where);
		if (isset($row->id)) {
			$this->_data = $row->toArray();
			$this->_cleanData = $this->_data;
			$this->_modifiedFields = array();
			return true;
		}
		return false;
	}
	
	/**
	 * Loads User by existing cookie
	 */
	public function loadFromCookie() {
		$request = Html5Wiki_Controller_Front::getInstance()->getRouter()->getRequest();
		if($request->getCookie('currentUserId')) {
			$this->loadById($request->getCookie('currentUserId'));
		 }
	}
	
	/**
	 * Save user cookie (currentUserId)
	 * @return bool return of setcookie.
	 */
	public function saveCookie() {
		return setcookie('currentUserId', $this->id, time() + 3600, '/', null, false, true);
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
		parent::save();
		
		$this->saveCookie($this->id);
	}


	/**
	 * @return 
	 */
	public function __toString() {
		return $this->name;
	}
}
?>