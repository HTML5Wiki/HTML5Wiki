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
	 * @param	Integer	$idMediaVersion
	 * @param	Integer	$timestampMediaVersion
	 */
	public function __construct($idMediaVersion, $timestampMediaVersion) {
		$idMediaVersion = intval($idMediaVersion);
		
		$this->load($idMediaVersion, $timestampMediaVersion);
	}
	
	/**
	 * 
	 * @param	Integer	$idMediaVersion
	 * @param	Integer	$timestampMediaVersion
	 */
	private function load($idMediaVersion, $timestampMediaVersion) {
		$table = new Html5Wiki_Model_Media_Table();
		
		$this->data	= $table->fetchMediaVersion($idMediaVersion, $timestampMediaVersion);
	}
}
?>

