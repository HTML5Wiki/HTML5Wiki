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
 * @author Nicolas Karrer <nkarrer@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @category Html5Wiki
 * @package Library
 * @subpackage Model
 */

/**
 * Media version table model
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
		'ARTICLE' => 'ARTICLE'
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
	 * Creates a new MediaVersion and returns its data.
	 *
	 * @param $saveData Array with MediaVersion information
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
	
	/**
	 * Updates the state for a MediaVersion.<br/>
	 * If the parameter $timestamp is not passed, ALL versions of a MediaVersions
	 * get updated with the state $newState.
	 *
	 * @param $newstate
	 * @param $id
	 * @param $timestamp (optional)
	 */
	public function updateState($newState, $id, $timestamp=false) {
		$data = array('state'=>$newState);
		$where = $this->getAdapter()->quoteInto('id = ?', $id);
		if($timestamp !== false && intval($timestamp) > 0) {
			$where .= $this->getAdapter()->quoteInto(' AND timestamp = ?', $timestamp);
		}
		
		$this->update($data, $where);
	}
	
}


?>