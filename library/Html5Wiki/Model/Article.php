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
 * @author	Nicolas Karrer <nkarrer@hsr.ch>
 *
 */
class Html5Wiki_Model_Article extends Html5Wiki_Model_Media {
	
	/**
	 * 
	 * @param	Integer	$idArticleVersion
	 * @param	Integer	$timestampArticleVersion
	 */
	public function __construct($idArticleVersion, $timestampArticleVersion) {
		parent::__construct($idArticleVersion, $timestampArticleVersion);
		
		$this->load($idArticleVersion, $timestampArticleVersion);
	}
	
	/**
	 * 
	 * @param	Integer	$idArticleVersion
	 * @param	Integer	$timestampArticleVersion
	 */
	private function load($idArticleVersion, $timestampArticleVersion) {
		$table = new Html5Wiki_Model_Article_Table();
		
		array_merge($this->data, $table->getArticleData($idArticleVersion, $timestampArticleVersion));
	}
}


?>