<?php
/**
 * This file is part of the HTML5Wiki Project.
 *
 * @author		Manuel Alabor <malabor@hsr.ch>
 * @copyright	(c) HTML5Wiki Team 2011
 * @package		Html5Wiki
 * @subpackage	Library
 */

/**
 * @author Manuel Alabor <malabor@hsr.ch>
 */
class Html5Wiki_Model_MediaVersion extends Zend_Db_Table_Row_Abstract {

	protected $_tableClass = 'Html5Wiki_Model_MediaVersion_Table';
	
	/**
	 * Automatically loads model data if necessary information is present.<br/>
	 * Following combinations are available:
	 * <ul>
	 *  <li>id & timestamp (loads specific version)</li>
	 *  <li>id (loads the latest version)</li>
	 *  <li>permalink (loads the latest version with given permalink)</li>
	 * </ul>
	 */
	public function init() {
		if(count($this->_cleanData) == 0) {
			$id = isset($this->id) ? intval($this->id) : 0;
			$timestamp = isset($this->timestamp) ? intval($this->timestamp) : 0;
			$permalink = isset($this->permalink) ? $this->permalink : '';

			if($permalink != '') {
				$this->loadLatestByPermalink($permalink);
			} elseif($id > 0 && $timestamp > 0) {
				$this->loadByIdAndTimestamp($id, $timestamp);
			} elseif($id > 0 && $timestamp == 0) {
				$this->loadLatestById();
			}
		}
	}
	
	/**
	 * Fills the model with the data of the latest version with the given
	 * permalink.
	 *
	 * @param $permalink
	 */
	private function loadLatestByPermalink($permalink) {
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
	private function loadByIdAndTimestamp($id, $timestamp) {
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
	private function loadById($id) {
		$select = $this->select();
		$select->order('timestamp DESC');
		$select->where('id = ?', $id);
		$select->where('state = ?', Html5Wiki_Model_MediaVersion_Table::getState('PUBLISHED'));
		
		$row = $this->_getTable()->fetchRow($select);
		
		$this->_data = $row->toArray();
		$this->_cleanData = $this->_data;
		$this->_modifiedFields = array();
	}
	
	public function getUser() {
		$data = array('id' => $this->userId);
		$user = new Html5Wiki_Model_User(array('data'=>$data));
		return $user;
	}
	
	public function save() {
		parent::save();
	}
	
	public function __toString() {
		return $this->permalink;
	}
	
}
?>
