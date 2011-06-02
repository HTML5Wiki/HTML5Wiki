<?php
/**
 * This file is part of the HTML5Wiki Project.
 * 
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.github.com/HTML5Wiki/HTML5Wiki/blob/master/LICENSE
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to mweibel@hsr.ch so we can send you a copy immediately.
 *
 * @author Manuel Alabor <malabor@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @category Html5Wiki
 * @package Library
 * @subpackage Model
 */

/**
 * Media version row model
 */
class Html5Wiki_Model_MediaVersion extends Html5Wiki_Model_Abstract {

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
		
		if ($mediaVersion) {
			$this->_data = $mediaVersion->toArray();
			$this->_cleanData = $this->_data;
			$this->_modifiedFields = array();
		}
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
	 * Loads a specific version of a MediaVersion into this Model.
	 *
	 * @param string $permalink
	 * @param int $timestamp
	 */
	public function loadByPermalinkAndTimestamp($permalink, $timestamp) {
		$select = $this->select();
		$select->where('permalink = ?', $permalink);
		$select->where('timestamp = ?', $timestamp);
		$select->where('state = ?', Html5Wiki_Model_MediaVersion_Table::getState('PUBLISHED'));

		$row = $this->_getTable()->fetchRow($select);
		
		if ($row) {
			$this->_data = $row->toArray();
			$this->_cleanData = $this->_data;
			$this->_modifiedFields = array();
		}
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
	
	/**
	 * Gets tags
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function getTags() {
		if (!isset($this->id) || !isset($this->timestamp)) {
			throw new Html5Wiki_Exception("MediaVersionRow must be fully loaded before getting tags");
		}
		
		$tags = new Html5Wiki_Model_MediaVersion_Mediatag_Table();
		
		return $tags->loadTags($this->id, $this->timestamp);
	}
	
	public function getUser() {
		$user = new Html5Wiki_Model_User();
		$user->loadById($this->userId);
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

	/**
	 * @return	String
	 */
	public function __toString() {
		return $this->permalink;
	}

	/**
	 * @return	Integer
	 */
	public function getTimestamp() {
		return intval($this->_data['timestamp']);
	}
	
}
?>

