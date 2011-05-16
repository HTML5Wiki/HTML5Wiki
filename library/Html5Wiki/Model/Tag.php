<?php
/**
 * This file is part of the HTML5Wiki Project.
 *
 * @author		Michael Weibel <mweibel@hsr.ch>
 * @copyright	(c) HTML5Wiki Team 2011
 * @package		Html5Wiki
 * @subpackage	Library
 */

class Html5Wiki_Model_Tag extends Zend_Db_Table_Row_Abstract {
	
	protected $_tableClass = 'Html5Wiki_Model_MediaVersion_Mediatag_Tag_Table';
	
	public function loadByTag($tag) {
		$select = $this->select();
		$select->where('tag = ?', $tag);
		
		$tag = $this->_getTable()->fetchRow($select);
		if (isset($tag->tag)) {
			$data = $tag->toArray();

			$this->_data = $data;
			$this->_cleanData = $this->_data;
			$this->_modifiedFields = array();
		}
	}
}

?>