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
class Html5Wiki_Model_Article extends Html5Wiki_Model_MediaVersion {
	
	/**
	 * 
	 * @var String
	 */
	private $mediaVersionType = 'ARTICLE';
	
	/**
	 * 
	 * @param	Integer	$idArticleVersion
	 * @param	Integer	$timestampArticleVersion
	 */
	public function __construct($idArticleVersion = 0, $timestampArticleVersion = 0) {
		parent::__construct($idArticleVersion, $timestampArticleVersion);
		
		$this->dbAdapter = new Html5Wiki_Model_Article_Table();
		
		$this->data['mediaVersionType'] = $this->mediaVersionType;
		
		$this->load($idArticleVersion, $timestampArticleVersion);
	}
	
	/**
	 * 
	 * @param	Integer	$idArticleVersion
	 * @param	Integer	$timestampArticleVersion
	 */
	private function load($idArticleVersion, $timestampArticleVersion) {
		$articleData = $this->dbAdapter->getArticleData($idArticleVersion, $timestampArticleVersion);
		
		if( $articleData != null) {
			$this->data = array_merge($this->data, $articleData->toArray());
		} else {
			$this->data['title'] = '';
			$this->data['content'] = '';
		}	
	}

	/**
	 * (non-PHPdoc)
	 * @see html5wiki/library/Html5Wiki/Model/Html5Wiki_Model_Media#save()
	 */
	public function save() {
		$primaryKeys = parent::save($this->data);
		
		$saveData							= $this->data;
		$saveData['mediaVersionId'] 		= $primaryKeys['id'];
		$saveData['mediaVersionTimestamp']	= $primaryKeys['timestamp'];
		
		$this->dbAdapter->saveArticle($saveData);
	}

	/**
	 * @return  String
	 */
	public function getTitle() {
		return $this->data['title'] ? $this->data['title'] : 'no Title';
	}
	
}


?>