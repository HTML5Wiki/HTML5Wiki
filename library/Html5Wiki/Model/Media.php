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
	 * 
	 * @param	Integer	$idMediaVersion
	 * @param	Integer	$timestampMediaVersion
	 */
	private function load($idMediaVersion, $timestampMediaVersion) {	
		$this->data	= $this->dbAdapter->fetchMediaVersion($idMediaVersion, $timestampMediaVersion);
		$this->data['tags'] = $this->dbAdapter->fetchMediaVersionTags($idMediaVersion);
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

