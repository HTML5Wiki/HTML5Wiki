<?php
/**
 * ArticleModelEngine
 *
 * @author Manuel Alabor <malabor@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @package Library
 * @subpackage SearchEngine
 */
class Html5Wiki_Search_ArticleModelEngine extends Html5Wiki_Search_ModelEngine_Abstract {

	private $_compatibleType = 'ARTICLE';

	public function prepareSearchStatement(Zend_Db_Select $select, $forTerm) {
		$forTerm = '%' . $forTerm . '%';
		$select->orWhere('ArticleVersion.title LIKE ?', $forTerm);
		$select->orWhere('ArticleVersion.content LIKE ?', $forTerm);
		$select->joinLeft('ArticleVersion',
			 'MediaVersion.id = ArticleVersion.mediaVersionId '
			.'AND MediaVersion.timestamp = ArticleVersion.mediaVersionTimestamp'
		);
		
		return $select;
	}
	
	protected function getModelClassName() {
		return "Html5Wiki_Model_ArticleVersion";
	}
	
	public function canPrepareModelForType($type) {
		return strtoupper($type) == $this->_compatibleType;
	}
	
}
?>