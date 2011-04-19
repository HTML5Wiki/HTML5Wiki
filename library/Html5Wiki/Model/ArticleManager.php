<?php

class Html5Wiki_Model_ArticleManager {
	
	/**
	 * 
	 * @var Html5Wiki_Model_Article_Table
	 */
	protected static $table = null;
	
	/**
	 * 
	 * @return unknown_type
	 */
	public static function table() {
		if( self::$table == null ) {
			 self::$table =  new Html5Wiki_Model_Media_Table();
		}
		
		return self::$table;
	}
	
	/**
	 * 
	 * @param	String	$permalink
	 * @return unknown_type
	 */
	public static function getArticleByPermaLink($permalink) {
		$articleId	= self::table()->fetchMediaVersionByPermaLink($permalink);
		
		if($articleId == null) {
			return $articleId;
		}
		
		return new Html5Wiki_Model_Article($articleId['mediaVersionId'], $articleId['mediaVersionTimestamp']);
	}
}
?>