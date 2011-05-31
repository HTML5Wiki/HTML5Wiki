<?php
/**
 * This file is part of the HTML5Wiki Project.
 *
 * @author		Nicolas Karrer <nkarrer@hsr.ch>
 * @copyright	(c) HTML5Wiki Team 2011
 * @package		Html5Wiki
 * @subpackage	Library
 */

class Html5Wiki_Model_User extends Html5Wiki_Model_Abstract {
	
	protected $_tableClass = 'Html5Wiki_Model_User_Table';
	
	/**
	 * Loads User by its id
	 * 
	 * @param int $userId
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
	
	/**
	 * Loads user by id, name and email
	 * @param int $id
	 * @param string $name
	 * @param string $email
	 * @return bool 
	 */
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
	 * @return string
	 */
	public function __toString() {
		return $this->name;
	}
}
?>