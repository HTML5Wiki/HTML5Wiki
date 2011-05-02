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
 * Defining of the Media table
 * 
 * @author Nicolas Karrer
 */
class Html5Wiki_Model_Media_Table extends Zend_Db_Table_Abstract {
	
	/**
	 * 
	 * @var string
	 */
	protected $_name		= 'MediaVersion';
	
	/**
	 * 
	 * @var	array
	 */
	protected $_primary		= array('id', 'timestamp');

	private static $MEDIA_VERSION_TYPE = array(
		'ARTICLE' => 'ARTICLE',
		'FILE' => 'FILE'
	);

	private static $STATE = array(
		'PUBLISHED' => 'PUBLISHED',
		'DRAFT' => 'DRAFT',
		'TRASH' => 'TRASH'
	);


	/**
	 * 
	 * @param	$idMediaVersion
	 * @param	$timestampMediaVersion
	 * @return	Array
	 */
	public function fetchMediaVersion($idMediaVersion, $timestampMediaVersion) {
		$selectStatement = $this->select()->setIntegrityCheck(false);
		
		$selectStatement->from($this);
		
		$selectStatement->where($this->_primary[1] . ' = ?', $idMediaVersion);

		if( $timestampMediaVersion > 0) {
			$selectStatement->where($this->_primary[2] . ' = ?', $timestampMediaVersion);
		} else {
			$selectStatement->limit(1);
			$selectStatement->order($this->_primary[2] . ' DESC');
		}

		
		return $this->fetchRow($selectStatement);
	}
	
	/**
	 * 
	 * @return Array
	 */
	public function saveMediaVersion($saveData) {
		$localSaveData = array();

		if(isset($saveData['id']))                  $localSaveData['id']                = $saveData['id'];
		if(isset($saveData['userId'])) 				$localSaveData['userId']			= $saveData['userId'];
		if(isset($saveData['permalink']))			$localSaveData['permalink']			= $saveData['permalink'];
		if(isset($saveData['state']))				$localSaveData['state']				= self::$STATE[$saveData['state']];
		if(isset($saveData['versionComment'])) 		$localSaveData['versioncomment']	= $saveData['versioncomment'];
		if(isset($saveData['mediaVersionType']))	$localSaveData['mediaVersionType']	= $saveData['mediaVersionType'];
		
		$localSaveData['timestamp'] = time();
		
		return $this->insert($localSaveData);
	} 
	
	
	
	
}


?>