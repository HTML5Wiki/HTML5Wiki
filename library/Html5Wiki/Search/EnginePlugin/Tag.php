<?php
/**
 * This file is part of the HTML5Wiki Project.
 * 
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * http://www.github.com/HTML5Wiki/HTML5Wiki/blob/master/LICENSE
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to mweibel@hsr.ch so we can send you a copy immediately.
 *
 * @author Manuel Alabor <malabor@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @category Html5Wiki
 * @package Library
 * @subpackage Search
 */

/**
 * Tag EnginePlugin
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
			$matchOrigins[] = 'tags';
		}
		
		return $matchOrigins;
	}
	
}
?>