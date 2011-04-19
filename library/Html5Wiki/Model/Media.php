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
 * 
 * @author Nicolas Karrer <nkarrer@hsr.ch>
 *
 */
class Html5Wiki_Model_Media {
	
	/**
	 * 
	 * @var	array
	 */
	protected $data	= array();
	
	/**
	 * 
	 * @var Zend_Db_Table_Abstract
	 */
	protected $dbAdapter;
	
	/**
	 * 
	 * @param	Integer	$idMediaVersion
	 * @param	Integer	$timestampMediaVersion
	 */
	public function __construct($idMediaVersion, $timestampMediaVersion) {
		$idMediaVersion = intval($idMediaVersion);
		
		$this->dbAdapter = new Html5Wiki_Model_Media_Table();
		
		$this->load($idMediaVersion, $timestampMediaVersion);
	}
	
	/**
	 * Fallback for direct member access.
	 * First it checks for a getter function, if not available try to find the data in $this->data
	 *
	 * @param	String		$memberName
	 * @return 	String
	 */
	public function __get($memberName) {
		$dataKey	= strtolower($memberName);
		$methodName	= 'get' . $memberName;

		if( method_exists($this, $methodName) ) {
			return call_user_func(array($this, $methodName));
		} elseif( array_key_exists($dataKey, $this->data) ) {
			return $this->data[$dataKey];
		}
		
		return '';
	}
	
	/**
	 * 
	 * @param	Integer	$idMediaVersion
	 * @param	Integer	$timestampMediaVersion
	 */
	private function load($idMediaVersion, $timestampMediaVersion) {	
		$this->data	= $this->dbAdapter->fetchMediaVersion($idMediaVersion, $timestampMediaVersion);
		//$this->data['tags'] = $this->dbAdapter->fetchMediaVersionTags($idMediaVersion);
	}
	
	/**
	 * 
	 * @param $data
	 * @return unknown_type
	 */
	public function setData(array $data) {
		$this->data = $data;
	}
	
	/**
	 * 
	 * @param	Array	$saveData
	 */
	public function save() {
		list($id, $timestamp) = $this->dbAdapter->saveMediaVersion($this->data);
		
		$this->data['id']			= $id;
		$this->data['timestamp']	= $timestamp;
		
		return array($id, $timestamp);
	}
}
?>

