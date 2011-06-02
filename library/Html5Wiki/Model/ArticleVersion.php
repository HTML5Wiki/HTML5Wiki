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
 * Article Version row
 */
class Html5Wiki_Model_ArticleVersion extends Html5Wiki_Model_MediaVersion {
	
	protected $_tableClass = 'Html5Wiki_Model_ArticleVersion_Table';
	
	/**
	 * Loads a a complete ArticleVersion, specified by its permalink, into this
	 * model.<br/>
	 * Since is not completly unique, the latest article with the given permalink
	 * gets loaded.
	 *
	 * @param $permalink
	 */
	public function loadLatestByPermalink($permalink) {
		// Get MediaVersion (permalink is available there):
		$mediaVersionTable = new Html5Wiki_Model_MediaVersion_Table();
		$select = $mediaVersionTable->select();
		$select->where('permalink = ?', $permalink);
		$select->where('state = ?', Html5Wiki_Model_MediaVersion_Table::getState('PUBLISHED'));
		$select->order('timestamp DESC');
		$mediaVersion = $mediaVersionTable->fetchRow($select);
		
		if (isset($mediaVersion->id)) {
			// Get ArticleVersion (article data lies there):
			$select = $this->select();
			$select->where('mediaVersionId = ?', $mediaVersion->id);
			$select->where('mediaVersionTimestamp = ?', $mediaVersion->timestamp);
			$articleVersion = $this->_getTable()->fetchRow($select);
			
			if (!isset($articleVersion->mediaVersionId)) {
				throw new Html5Wiki_Exception("MediaVersion {$mediaVersion->id} does not have an articleVersion!");
			}

			$articleVersionData = $articleVersion->toArray();
			$mediaVersionData = $mediaVersion->toArray();
			$data = array_merge($articleVersionData,$mediaVersionData);

			$this->_data = $data;
			$this->_cleanData = $this->_data;
			$this->_modifiedFields = array();
		}
	}
	
	/**
	 * Loads a complete ArticleVersion, specified by its ID and Timestamp, into
	 * this Model.
	 *
	 * @param $id
	 * @param $timestamp
	 */
	public function loadByIdAndTimestamp($id, $timestamp) {
		$select = $this->select();
		$select->where('mediaVersionId = ?', $id);
		$select->where('mediaVersionTimestamp = ?', $timestamp);
		
		$articleVersion = $this->_getTable()->fetchRow($select);
		if ($articleVersion) {
			$mediaVersion = $articleVersion->findParentRow('Html5Wiki_Model_MediaVersion_Table', 'MediaVersion');

			$articleVersionData = $articleVersion->toArray();
			$mediaVersionData = $mediaVersion->toArray();
			$data = array_merge($articleVersionData,$mediaVersionData);

			$this->_data = $data;
			$this->_cleanData = $this->_data;
			$this->_modifiedFields = array();
		}
	}
	
	/**
	 * Loads the latest version of an ArticleVersion, specified by its ID, into
	 * this Model.
	 *
	 * @param $id
	 */
	public function loadLatestById($id) {
		$select = $this->select();
		$select->where('mediaVersionId = ?', $id);
		$select->order('mediaVersionTimestamp DESC');
		
		$articleVersion = $this->_getTable()->fetchRow($select);
		if ($articleVersion) {
			$mediaVersion = $articleVersion->findParentRow('Html5Wiki_Model_MediaVersion_Table','MediaVersion');

			$articleVersionData = $articleVersion->toArray();
			$mediaVersionData = $mediaVersion->toArray();

			$data = array_merge($articleVersionData, $mediaVersionData);

			$this->_data = $data;
			$this->_cleanData = $this->_data;
			$this->_modifiedFields = array();
		}
	}
	
	/**
	 * Delivers the title of this article.<br/>
	 * This method is essential for the MediaVersion-compliance.
	 *
	 * @see MediaVersion#getName
	 */
	public function getCommonName() {
		return $this->title;
	}

	/**
	 * @return int
	 */
	public function getMediaVersionTimestamp() {
		return intval($this->_data['mediaVersionTimestamp']);
	}
	
}

?>