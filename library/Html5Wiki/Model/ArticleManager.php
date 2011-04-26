<?php

class Html5Wiki_Model_ArticleManager {
	
	/**
	 * 
	 * @var Html5Wiki_Model_Article_Table
	 */
	protected static $table = null;
	
	/**
	 * 
	 * @return  Html5Wiki_Model_Article_Table
	 */
	public static function table() {
		if( self::$table == null ) {
			 self::$table =  new Html5Wiki_Model_Article_Table();
		}
		
		return self::$table;
	}
	
	/**
	 * 
	 * @param	String	$permalink
	 * @return unknown_type
	 */
	public static function getArticleByPermaLink($permalink) {
		$articleId	= self::table()->fetchArticleByPermaLink($permalink);
		
		if($articleId == null) {
			return $articleId;
		}
		
		return new Html5Wiki_Model_Article($articleId['mediaVersionId'], $articleId['mediaVersionTimestamp']);
	}

	/**
	 * @static
	 * @param  $idArticle
	 * @return array
	 */
	public static function getArticlesById($idArticle) {
		$articleArray = array();
		$articles   = self::table()->fetchArticlesById($idArticle);

		foreach($articles as $article) {
			$articleArray[] = new Html5Wiki_Model_Article($article['mediaVersionId'], $article['mediaVersionTimestamp']);
		}

		return $articleArray;
	}
}
?>