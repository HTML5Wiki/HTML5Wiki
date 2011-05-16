<?php
/**
 * This file is part of the HTML5Wiki Project.
 *
 * @author		Nicolas Karrer <nkarrer@hsr.ch>
 * @copyright	(c) HTML5Wiki Team 2011
 * @package		Html5Wiki
 * @subpackage	Library
 */


class Html5Wiki_Model_MediaVersion_Mediatag_Table extends Zend_Db_Table_Abstract {
		
	/**
	 * 
	 * @var string
	 */
	protected $_name		= 'MediaVersionTag';
	
	protected $_rowClass	= 'Html5Wiki_Model_MediaVersion_Tag';
	
	/**
	 * 
	 * @var array
	 */
	protected $_primary		= array('tagTag', 'mediaVersionId', 'mediaVersionTimestamp');
	
	/**
	 * 
	 * @var boolean
	 */
	protected $_sequence	= false;
	
	protected $_dependentTables = array('Html5Wiki_Model_MediaVersion_Table');
	protected $_referenceMap = array(
		'MediaVersion' => array(
			'columns' => array('mediaVersionId','mediaVersionTimestamp')
			,'refTableClass' => 'Html5Wiki_Model_MediaVersion_Table'
			,'refColumns' => array('id','timestamp')
		)
	);
	
	public function loadTags($id, $timestamp) {
		$select = $this->select();
		$select->where('mediaVersionId = ?', $id);
		$select->where('mediaVersionTimestamp = ?', $timestamp);
		
		return $this->fetchAll($select);
	}
}
?>