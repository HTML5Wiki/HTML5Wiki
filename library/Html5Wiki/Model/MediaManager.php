<?php
/**
 * This file is part of the HTML5Wiki Project.
 *
 * @author		Manuel Alabor
 * @copyright	(c) HTML5Wiki Team 2011
 * @package		Html5Wiki
 * @subpackage	Library
 */

/**
 * Provides methods and processes to access MediaVersion information on the
 * database.<br/>
 * Especially fetching of a version history is handled here.
 *
 * @author	Manuel Alabor
 */
class Html5Wiki_Model_MediaManager {
	
	private $table = null;
	
	private __construct() {
		$this->table = new Html5Wiki_Model_Media_Table();
	}
	
	/**
	 * Returns the latest version of a Media by looking for its permalink.
	 *
	 * @param $permalink
	 * @return MediaVersion (ZendDB-representation)
	 */
	public function getMediaByPermaLink($permalink) {
		$articleId	= $this->table->fetchArticleByPermaLink($permalink);
		
		if($articleId == null) {
			return $articleId;
		}
		
		return new Html5Wiki_Model_Article($articleId['mediaVersionId'], $articleId['mediaVersionTimestamp']);
	}

	/**
	 * Returns ALL versions of a media by looking for its ID.
	 *
	 * @param  $idMediaVersion
	 * @return Array with MediaVersions (ZendDB-representation)
	 */
	public function getMediaVersionsById($idMediaVersion) {
		$articleArray = array();
		$articles   = $this->table->fetchArticlesById($idMediaVersion);

		foreach($articles as $article) {
			$articleArray[] = new Html5Wiki_Model_Article($article['mediaVersionId'], $article['mediaVersionTimestamp']);
		}

		return $articleArray;
	}




	/**
	 * Analyzes a Timestamp and recognizes its position relativly in the past.<br/>
	 * Example: If the timestamp is located yesterday, the method returns "yesterday".
	 *
	 * @param  $timestamp
	 * @return timespan-Code (today,yesterday,daybeforeyesterday,thisweek,lastweek)
	 */
	private function getTimespanGroup($timestamp) {
		$timestamp = intval($timestamp);

		$today = array(
			'start' => mktime(0, 0, 0, date('n'), date('d'), date('Y')),
			'end' => mktime(23, 59, 59, date('n'), date('d'), date('Y')),
		);
		$yesterday = array(
			'start' => $today['start'] - 24 * 3600,
			'end'   => $today['end'] - 24 * 3600,
		);
		$dayBeforeYesterday = array(
			'start' => $yesterday['start'] - 24 * 3600,
			'end'   => $yesterday['end'] - 24 * 3600,
		);
		$thisWeek = array(
			'start' => self::getWeekStart(time()),
			'end'   => self::getWeekEnd(time()),
		);
		$lastWeek = array(
			'start' => self::getWeekStart(time() - 7 * 24 * 3600),
			'end'   => self::getWeekEnd(time()  - 7 * 24 * 3600),
		);

		if( $timestamp < $today['end'] && $timestamp > $today['start']) {
			return 'today';
		} else if( $timestamp < $yesterday['end'] && $timestamp > $yesterday['start']) {
			return 'yesterday';
		} else if( $timestamp < $dayBeforeYesterday['end'] && $timestamp > $dayBeforeYesterday['start']) {
			return 'daybeforeyesterday';
		} else if( $timestamp < $thisWeek['end'] && $timestamp > $thisWeek['start']) {
			return 'thisweek';
		} else if( $timestamp < $lastWeek['end'] && $timestamp > $lastWeek['start']) {
			return 'lastweek';
		} else {
			return 'month';
		}
	}

	/**
	 * Takes a timestamp and delivers the regarding week (or more precise: the
	 * timestamp of the weeks start)
	 *
	 * @param $timestamp
	 * @return weekstart as timestamp
	 */
	private function getWeekStart($timestamp) {
		$diff = (date('w', $timestamp)+6)%7;
		return mktime(0, 0, 0, date('n', $timestamp), date('j', $timestamp)-$diff, date('Y', $timestamp));
	}

	/**
	 * Takes a timestamp and delivers the regarding week (or more precise: the
	 * timestamp of the weeks end)
	 *
	 * @param $timestamp
	 * @return weekend as timestamp
	 */
	private function getWeekEnd($timestamp) {
		$diff = (7-date('w', $timestamp))%7;
		return mktime(23, 59, 59, date('n', $timestamp), date('j', $timestamp)+$diff, date('Y', $timestamp));
	}
}
?>