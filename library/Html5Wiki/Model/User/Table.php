<?php
/**
 * This file is part of the HTML5Wiki Project.
 * 
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * http://www.github.com/HTML5Wiki/HTML5Wiki/blob/master/LICENSE
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to mweibel@hsr.ch so we can send you a copy immediately.
 *
 * @author Nicolas Karrer <nkarrer@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @category Html5Wiki
 * @package Library
 * @subpackage Model
 */

/**
 * User table model
 */
class Html5Wiki_Model_User_Table extends Zend_Db_Table_Abstract {
			
	protected $_name = 'User';
	protected $_primary = array('id');
	protected $_rowClass = 'Html5Wiki_Model_User';
	
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
		$where	= $this->getAdapter()->quoteInto($this->_primary[0] . ' = ?', $idUser);
		
		$updateData = array(
			'email'	=> $data['email'],
			'name'	=> $data['name']
		);
		
		$this->update($updateData, $where);
	}

	/**
	 * @param	String		$name
	 * @param	String		$email
	 * @return	Html5Wiki_Library_Model_User
	 */
    public function userExists($name, $email) {
        $selectStatement = $this->select()->setIntegrityCheck(false);

        $selectStatement->from($this);

        $selectStatement->where('name = ?', $name);
        $selectStatement->where('email = ?', $email);

        return $this->fetchRow($selectStatement);
	}
}
?>