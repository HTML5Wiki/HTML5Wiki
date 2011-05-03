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
	protected $_sequence	= true;

	/**
	 * 
	 */
	public function getArticleData($idMediaVersion, $timestampMediaVersion) {
		$selectStatement = $this->select()->setIntegrityCheck(false);
		
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
	 * @param $data
	 * @return unknown_type
	 */
	public function saveArticle($saveData) {
		$localSaveData = array(
			'mediaVersionId' 		=> intval($saveData['mediaVersionId']),
			'mediaVersionTimestamp' => intval($saveData['mediaVersionTimestamp']),
			'title'					=> $saveData['title'],
		);
		
		if(isset($saveData['content'])) $localSaveData['content'] = $saveData['content'];

		return $this->insert($localSaveData);
	}
	
	/**
	 * @param  $permalink
	 * @return null|Zend_Db_Table_Row_Abstract
	 */
	public function fetchArticleByPermaLink($permalink) {
		$selectStatement = $this->initSelectStatement('PUBLISHED');

		$selectStatement->where('permalink = ?', $permalink);

		$selectStatement = $this->addMediaVersionJoinStatement($selectStatement);

		$selectStatement->limit(1);
		$selectStatement->order('timestamp DESC');

		return $this->fetchRow($selectStatement);
	}

	/**
	 * @param  $idArticle
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function fetchArticlesById($idArticle) {
		$idArticle  = intval($idArticle);

		$selectStatement = $this->initSelectStatement('PUBLISHED');

		$selectStatement->where('mediaVersionId = ?', $idArticle);

		$selectStatement = $this->addMediaVersionJoinStatement($selectStatement);

		$selectStatement->order('timestamp DESC');

		return $this->fetchAll($selectStatement);
	}
	
	/**
	 * @return type 
	 */
	public function fetchLatestArticles() {
		
		$select = $this->select()->setIntegrityCheck(false);
		
		$subselect = $this->select()->setIntegrityCheck(false)
				->from('MediaVersion')
				->where('mediaVersionType = ?', 'ARTICLE')
				->where('state = ?', 'PUBLISHED')
				->order('timestamp DESC');
		
		$select->from($subselect);
		
		$idJoinCondition = 't.id = ' . $this->_name . '.' . $this->_primary[1];
		$timestampJoinCondition =  't.timestamp = ' . $this->_name . '.' . $this->_primary[2];
		
		$select->join('ArticleVersion', $idJoinCondition . ' AND ' . $timestampJoinCondition);
		$select->group('t.id');
		$select->order('t.timestamp DESC');
		
		return $this->fetchAll($select);
	}

	/**
	 * @param  $state
	 * @return Zend_Db_Select
	 */
	private function initSelectStatement($state) {
		$selectStatement = $this->select()->setIntegrityCheck(false);
		$selectStatement->from($this);
		$selectStatement->where('mediaVersionType = ?', 'ARTICLE');
		$selectStatement->where('state = ?', $state);

		return $selectStatement;
	}

	/**
	 * Add Join to the select statement
	 *
	 * @param   Zend_Db_Select    $selectStatement
	 * @return  Zend_Db_Select
	 */
	private function addMediaVersionJoinStatement($selectStatement) {
		$idJoinCondition = $this->_name . '.' . $this->_primary[1] . ' = MediaVersion.id';
		$timestampJoinCondition =  $this->_name . '.' . $this->_primary[2] . ' = MediaVersion.timestamp';
		$selectStatement->join('MediaVersion', $idJoinCondition . ' AND ' . $timestampJoinCondition);

		return $selectStatement;
	}
}


?>