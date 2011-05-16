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
class Html5Wiki_Model_MediaVersion_Table extends Zend_Db_Table_Abstract {
	
	protected $_name		= 'MediaVersion';
	protected $_primary		= array('id', 'timestamp');
	protected $_rowClass	= 'Html5Wiki_Model_MediaVersion';
	
	protected $_dependentTables = array('Html5Wiki_Model_User_Table');
	protected $_referenceMap = array(
		'User' => array(
			'columns' => array('userId')
			,'refTableClass' => 'Html5Wiki_Model_User_Table'
			,'refColumns' => array('id')
		)
	);
	
	
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
	 * Returns specified state enum value
	 * @param string $state
	 * @return string 
	 */
	public static function getState($state) {
		return self::$STATE[$state];
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

		if( $timestampMediaVersion > 0) {
			$selectStatement->where($this->_primary[2] . ' = ?', $timestampMediaVersion);
		} else {
			$selectStatement->limit(1);
			$selectStatement->order($this->_primary[2] . ' DESC');
		}

		
		return $this->fetchRow($selectStatement);
	}
	
	/**
	 * Returns the latest version of a media regarding its permalink.
	 *
	 * @param  $permalink
	 * @return null or Zend_Db_Table_Row_Abstract
	 */
	public function fetchMediaByPermaLink($permalink) {
		$selectStatement = $this->select()->setIntegrityCheck(false);
		$selectStatement->from($this);
		$selectStatement->where('permalink = ?', $permalink);
		$selectStatement->where('state = ?', self::$STATE['PUBLISHED']);

		$selectStatement->limit(1);
		$selectStatement->order('timestamp DESC');

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
	
	public function search($term) {
		$select = $this->select()->setIntegrityCheck(false);
		$select->from($this);
		if (is_int($term)) {
			$select->where('id = ?', $term);
		} else {
			$term = '%' . $term . '%';
			$select->orWhere('ArticleVersion.title LIKE ?', $term);
			$select->orWhere('ArticleVersion.content LIKE ?', $term);
			$select->orWhere('MediaVersionTag.tagTag LIKE ?', $term);
		}
		$select->where('state = ?', self::$STATE['PUBLISHED']);
		
		$select->join('ArticleVersion', 'MediaVersion.id = ArticleVersion.mediaVersionId AND MediaVersion.timestamp = ArticleVersion.mediaVersionTimestamp');
		$select->join('MediaVersionTag', 'MediaVersion.id = MediaVersionTag.mediaVersionId AND MediaVersion.timestamp = MediaVersionTag.mediaVersionTimestamp');
		
		$select->group('id');
		$select->order('timestamp DESC');
		
		return $this->fetchAll($select);
	}
	
	
}


?>