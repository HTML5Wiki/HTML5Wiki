<?php
/**
 * This file is part of the HTML5Wiki Project.
 *
 * @author		Nicolas Karrer <nkarrer@hsr.ch>
 * @copyright	(c) HTML5Wiki Team 2011
 * @package		Html5Wiki
 * @subpackage	Library
 */


class Html5Wiki_Model_File_Table extends Zend_Db_Table_Abstract {
		
	/**
	 * 
	 * @var string
	 */
	protected $_name		= 'FileVerson';
	
	/**
	 * 
	 * @var array
	 */
	protected $_primary		= array('mediaVersionId', 'mediaVersionTimestamp');
	
	/**
	 * 
	 * @var boolean
	 */
	protected $_sequence	= false;
	
	/**
	 * 
	 */
	public function getFileData($idMediaVersion, $timestampMediaVersion) {
		$selectStatement = $this->select()->setIntegrityCheck(false);
		
		$selectStatement->where($this->_primary[1] . ' = ?', $idMediaVersion);
		$selectStatement->where($this->_primary[2] . ' = ?', $timestampMediaVersion);
		
		return $this->fetchRow($selectStatement);
	}
	
	/**
	 * 
	 * @param $data
	 * @return unknown_type
	 */
	public function saveFile($data) {
		$saveData = array(
			'mediaVersionId' 		=> intval($data['mediaVersionId']),
			'mediaVersionTimestamp' => intval($data['mediaVersionTimetamp']),
			'name'					=> $data['name'],
			'filepath' 				=> $data['filepath'],
			'description'			=> $data['description'],
			'origin'				=> $data['origin'],
			'author'				=> $data['author'],
			'mimetypeId'			=> $data['mimetypeId'],
			'licenseId'				=> $data['licenseId']
		);
		
		return $this->insert($saveData);
	}
	
		
}
?>