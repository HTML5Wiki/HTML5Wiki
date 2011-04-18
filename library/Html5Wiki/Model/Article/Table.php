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
 * Defining of the Article table
 * 
 * @author Nicolas Karrer
 */
class Html5Wiki_Model_Article_Table extends Zend_Db_Table_Abstract {
	
	/**
	 * 
	 * @var string
	 */
	protected $_name		= 'ArticleVersion';
	
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
	public function getArticleData($idMediaVersion, $timestampMediaVersion) {
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
	public function saveArticle($data) {
		$saveData = array(
			'mediaVersionId' => intval($data['mediaVersionId']),
			'mediaVersionTimestamp' => intval($data['mediaVersionTimetamp']),
			'title'	=> $data['title'],
			'content' => $data['content']
		);
		
		return $this->insert($saveData);
	}
	

}


?>