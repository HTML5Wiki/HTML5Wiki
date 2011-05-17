<?php
/**
 * This file is part of the HTML5Wiki Project.
 *
 * @author		Manuel Alabor <malabor@hsr.ch>
 * @copyright	(c) HTML5Wiki Team 2011
 * @package		Html5Wiki
 * @subpackage	Library
 */

class Html5Wiki_Model_MediaVersion extends Zend_Db_Table_Row_Abstract {

	protected $_tableClass = 'Html5Wiki_Model_MediaVersion_Table';
	
	/**
	 * Fills the model with the data of the latest version with the given
	 * permalink.
	 *
	 * @param $permalink
	 */
	public function loadLatestByPermalink($permalink) {
		$select = $this->select();
		$select->order('timestamp DESC');
		$select->where('permalink = ?', $permalink);
		$select->where('state = ?', Html5Wiki_Model_MediaVersion_Table::getState('PUBLISHED'));
		
		$mediaVersion = $this->_getTable()->fetchRow($select);
		
		$this->_data = $mediaVersion->toArray();
		$this->_cleanData = $this->_data;
		$this->_modifiedFields = array();
	}
	
	/**
	 * Loads a specific version of a MediaVersion into this Model.
	 *
	 * @param $id
	 * @param $timestamp
	 */
	public function loadByIdAndTimestamp($id, $timestamp) {
		$select = $this->select();
		$select->where('id = ?', $id);
		$select->where('timestamp = ?', $timestamp);
		$select->where('state = ?', Html5Wiki_Model_MediaVersion_Table::getState('PUBLISHED'));
		
		$row = $this->_getTable()->fetchRow($select);
		
		$this->_data = $row->toArray();
		$this->_cleanData = $this->_data;
		$this->_modifiedFields = array();
	}
	
	/**
	 * Loads the latest version of a MediaVersion into this Model.
	 *
	 * @param $id
	 */
	public function loadById($id) {
		$select = $this->select();
		$select->order('timestamp DESC');
		$select->where('id = ?', $id);
		$select->where('state = ?', Html5Wiki_Model_MediaVersion_Table::getState('PUBLISHED'));
		
		$row = $this->_getTable()->fetchRow($select);
		
		$this->_data = $row->toArray();
		$this->_cleanData = $this->_data;
		$this->_modifiedFields = array();
	}
	
	public function getTags() {
		if (!isset($this->id) || !isset($this->timestamp)) {
			throw new Html5Wiki_Exception("MediaVersionRow must be fully loaded before getting tags");
		}
		
		$tags = new Html5Wiki_Model_MediaVersion_Mediatag_Table();
		
		return $tags->loadTags($this->id, $this->timestamp);
	}
	
	public function getUser() {
		$data = array('id' => $this->userId);
		$user = new Html5Wiki_Model_User(array('data'=>$data));
		return $user;
	}
	
	/**
	 * This Method has to be overwritten by the specific *Version-Model.
	 * It should deliver the title, the name or something comparable.
	 *
	 * @returns Name as String
	 */
	public function getCommonName() {
		return '';
	}
	
	public function save() {
		parent::save();
	}
	
	public function __toString() {
		return $this->permalink;
	}
	
}
?>

