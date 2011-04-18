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
class Html5Wiki_Model_User {
	
	/**
	 * 
	 * @var unknown_type
	 */
	protected $id = 0;
	
	/**
	 * 
	 * @var	array
	 */
	protected $data	= array();
	
	/**
	 * 
	 * @var Zend_Db_Table_Abstract
	 */
	protected $dbAdapter;
	
	/**
	 * 
	 * @param	Integer	$idUser
	 */
	public function __construct($idUser) {
		$this->id	= intval($idUser);
		
		$this->dbAdabter = new Html5Wiki_Model_User_Table();
		
		if( $this->id > 0 ) {
			$this->load();
		}
	}
	
	/**
	 * Loads User by its id
	 */
	private function load() {	
		$this->data = $this->dbAdapter->fetchUser($this->id);
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