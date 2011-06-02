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
 * Article EnginePlugin
 */
class Html5Wiki_Search_EnginePlugin_Article extends Html5Wiki_Search_EnginePlugin_Abstract {

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
	
	public function getMatchOrigins($forTerm, $model) {
		$matchOrigins = array();
		
		if($model instanceof Html5Wiki_Model_ArticleVersion) {
			if(stripos($model->title, $forTerm) !== FALSE) $matchOrigins[] = 'title';
			if(stripos($model->content, $forTerm) !== FALSE) $matchOrigins[] = 'content';
		}
		
		return $matchOrigins;
	}
	
}
?>