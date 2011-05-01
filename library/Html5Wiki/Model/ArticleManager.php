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

	/**
	 * @static
	 * @param  $timestamp
	 * @return void
	 */
	public static function getTimespanGroup($timestamp) {
		$timestamp = intval($timestamp);

		$today      = array(
			'start' => mktime(0, 0, 0, date('n'), date('d'), date('Y')),
			'end' => mktime(23, 59, 59, date('n'), date('d'), date('Y')),
		);
		$yesterday  = array(
			'start' => $today['start'] - 24 * 3600,
			'end'   => $today['end'] - 24 * 3600,
		);
		$dayBeforeYesterday   = array(
			'start' => $yesterday['start'] - 24 * 3600,
			'end'   => $yesterday['end'] - 24 * 3600,
		);
		$thisWeek = array(
			'start' => self::getWeekStart(time()),
			'end'   => self::getWeekEnd(time()),
		);
		$lastWeek   = array(
			'start' => self::getWeekStart(time() - 7 * 24 * 3600),
			'end'   => self::getWeekEnd(time()  - 7 * 24 * 3600),
		);

		if( $timestamp < $today['end'] && $timestamp > $today['start']) {
			return 'Heute';
		} else if( $timestamp < $yesterday['end'] && $timestamp > $yesterday['start']) {
			return 'Gestern';
		} else if( $timestamp < $dayBeforeYesterday['end'] && $timestamp > $dayBeforeYesterday['start']) {
			return 'Vorgestern';
		} else if( $timestamp < $thisWeek['end'] && $timestamp > $thisWeek['start']) {
			return 'Diese Woche';
		} else if( $timestamp < $lastWeek['end'] && $timestamp > $lastWeek['start']) {
			return 'Letzte Woche';
		} else {
			return date('F Y', $timestamp);
		}
	}

	public static function getWeekStart($timestamp) {
		$diff	= (date('w', $timestamp)+6)%7;

		return mktime(0, 0, 0, date('n', $timestamp), date('j', $timestamp)-$diff, date('Y', $timestamp));
	}

	public static function getWeekEnd($timestamp) {
		$diff	= (7-date('w', $timestamp))%7;

		return mktime(23, 59, 59, date('n', $timestamp), date('j', $timestamp)+$diff, date('Y', $timestamp));
	}
}
?>