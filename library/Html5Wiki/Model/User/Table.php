<?php
/**
 * This file is part of the HTML5Wiki Project.
 *
 * @author		Nicolas Karrer <nkarrer@hsr.ch>
 * @copyright	(c) HTML5Wiki Team 2011
 * @package		Html5Wiki
 * @subpackage	Library
 */

class Html5Wiki_Model_User_Table extends Zend_Db_Table_Abstract {
			
	/**
	 * 
	 * @var string
	 */
	protected $_name		= 'User';
	
	/**
	 * 
	 * @var array
	 */
	protected $_primary		= array('id');
	
	/**
	 * 
	 * @param	$idUser
	 * @return	Array
	 */
	public function fetchUser($idUser) {
		$selectStatement = $this->select()->setIntegrityCheck(false);
		
		$selectStatement->from($this);
		
		$selectStatement->where($this->_primary[1] . ' = ?', $idUser);
		
		return $this->fetchRow($selectStatement);
	}
	
	/**
	 * saves new user to database. returns new Id
	 * 
	 * @param	Array	$saveData
	 * @return	Integer	Id
	 */
	public function saveNewUser($data) {
		$saveData = array(
			'email'	=> $data['email'],
			'name'	=> $data ['name']
		);
		
		return $this->insert($saveData);
	}
	
	/**
	 * Updates user data
	 * 
	 * @param	Integer		$idUser
	 * @param	Array		$data
	 */
	public function updateUser($idUser, $data) {
		$where	= $this->getAdapter()->quoteInto($this->_primary . ' = ', $idUser);
		
		$updateData = array(
			'email'	=> $data['email'],
			'name'	=> $data['name']
		);
		
		$this->update($data, $where);
	}

    public function userExists($name, $email) {
        $selectStatement = $this->select()->setIntegrityCheck(false);

        $selectStatement->from($this);

        $selectStatement->where('name = ?', $name);
        $selectStatement->where('email = ?', $email);

        return $this->fetchRow($selectStatement);
    }
}
?>