<?php
/**
 * Tag EnginePlugin
 *
 * @author Manuel Alabor <malabor@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @package Library
 * @subpackage Search
 */
class Html5Wiki_Search_EnginePlugin_Tag extends Html5Wiki_Search_EnginePlugin_Abstract {

	public function prepareSearchStatement(Zend_Db_Select $select, $forTerm) {
		$select->orWhere('MediaVersionTag.tagTag LIKE ?', '%' . $forTerm . '%');
		$select->joinLeft('MediaVersionTag',
			'MediaVersion.id = MediaVersionTag.mediaVersionId '
			.'AND MediaVersion.timestamp = MediaVersionTag.mediaVersionTimestamp'
		);
		
		return $select;
	}
	
	protected function getModelClassName() {
		return '';
	}
	
	public function canPrepareModelForType($type) {
		return false;
	}
	
	public function getMatchOrigins($forTerm, $model) {
		$matchOrigins = array();
		
		if(stripos($model->tagTag, $forTerm) !== FALSE) {
			$matchOrigins[] = 'tag';
		}
		
		return $matchOrigins;
	}
	
}
?>