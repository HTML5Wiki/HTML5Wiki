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
		
	}
	
	protected function getModelClassName() {
		return "Html5Wiki_Model_ArticleVersion";
	}
	
	public function canPrepareModelForType($type) {
		return strtoupper($type) == $this->_compatibleType;
	}
	
}
?>