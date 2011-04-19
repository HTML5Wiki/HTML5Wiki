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

	// @todo move this to article_table
	// @todo remove join (media don't know article)
	public function fetchMediaVersionByPermaLink($permalink, $mediaVersionType = 'ARTICLE', $state = 'PUBLISHED') {
		$selectStmt = $this->select()->setIntegrityCheck(false);
		$selectStmt->from($this);
		$selectStmt->where('mediaVersionType = ?', $mediaVersionType);
		$selectStmt->where('state = ?', $state);
		$selectStmt->where('permalink = ?', $permalink);


		$idJoinCondition = $this->_name . '.' . $this->_primary[1] . ' = ArticleVersion.mediaVersionId';
		$timestampJoinCondition =  $this->_name . '.' . $this->_primary[2] . ' = ArticleVersion.mediaVersionTimestamp';
		$selectStmt->join('ArticleVersion', $idJoinCondition . ' AND ' . $timestampJoinCondition);

		return $this->fetchRow($selectStmt);
	}
	
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
		$selectStatement->where($this->_primary[2] . ' = ?', $timestampMediaVersion);
		
		return $this->fetchRow($selectStatement);
	}
	
	/**
	 * 
	 * @return Array
	 */
	public function saveMediaVersion($saveData) {
		$localSaveData = array();

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