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
class Html5Wiki_Model_MediaVersionManager {
	
	/**
	 * This method delivers all versions of a specific Media regarding its
	 * permalink.<br/>
	 * To achieve this, the ID of the Media has to be fetched. After getting the
	 * ID, all versions of the Media can be retrived.
	 *
	 * @see MediaManager#getMediaByPermalink
	 * @param string $permalink
	 * @return Zend_Db_Table_Rowset
	 */
	public function getMediaVersionsByPermalink($permalink) {
		$versions = null;
		$permalinksModel = $this->getLatestMediaVersionByPermaLink($permalink);
		
		if($permalinksModel->id > 0) {
			$versions = $this->getMediaVersionsById($permalinksModel->id);
		}
		
		return $versions;
	}
	
	/**
	 * Fetches media versions by id and one or more timestamps.<br/>
	 * To achieve this, the ID of the Media has to be fetched. After getting the
	 * ID, all demanded versions of the Media can be retrived.
	 *
	 * @param string $permalink
	 * @param array $timestamps
	 * @return type 
	 */
	public function getMediaVersionsByPermalinkAndTimestamps($permalink, array $timestamps) {
		$versions = null;
		$permalinksModel = $this->getLatestMediaVersionByPermaLink($permalink);
		
		if($permalinksModel->id > 0) {
			$id = $permalinksModel->id;
			$table = new Html5Wiki_Model_MediaVersion_Table();
			$select = $table->select()->setIntegrityCheck(false);

			$select->where('id = ?', $id);
			$select->where('state = ?', Html5Wiki_Model_MediaVersion_Table::getState('PUBLISHED'));
			$select->where('timestamp in (?)', $timestamps);

			echo $select;
			$versions = $table->fetchAll($select);
		}
		
		return $versions;
	}
	
	/**
	 * Returns the latest version of a Media by looking for its permalink.
	 *
	 * @param $permalink
	 * @return Html5Wiki_Model_MediaVersion
	 */
	public function getLatestMediaVersionByPermaLink($permalink) {
		$model = new Html5Wiki_Model_MediaVersion();
		$model->loadLatestByPermalink($permalink);
		
		return $model;
	}

	/**
	 * Returns a rowset of ALL versions of a MediaVersion by looking for its ID.
	 *
	 * @param  $idMediaVersion
	 * @return Zend_Db_Table_Rowset
	 */
	public function getMediaVersionsById($id) {
		$model = new Html5Wiki_Model_MediaVersion();
		$select = $model->select();
		
		$select->where('id = ?', $id);
		$select->order('timestamp DESC');
		$rowset = $model->getTable()->fetchAll($select);
		
		return $rowset;
	}
	
	/**
	 * Groups MediaVersions into proper temporal groups by using
	 * Html5Wiki_Model_MediaManager#getTimespanGroup.<br/>
	 *
	 * @param $versions Array with Html5Wiki_Model_MediaVersion
	 * @return Associative Array with Html5Wiki_Model_MediaVersion
	 */
	public function groupMediaVersionByTimespan($versions) {
		$groupedVersions = array();
		
		foreach($versions as $version) {
			$group = $this->getTimespanGroup($version->timestamp);
			$groupedVersions[$group][] = $version;
		}
		
		return $groupedVersions;
	}


	/**
	 * Analyzes a Timestamp and recognizes its position relativly in the past.<br/>
	 * Example: If the timestamp is located yesterday, the method returns "yesterday".
	 *
	 * @param  $timestamp
	 * @return timespan-Code (today,yesterday,daybeforeyesterday,thisweek,lastweek)
	 * @author Nicolas Karrer <nkarrer@hsr.ch>
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
	 * @author Nicolas Karrer <nkarrer@hsr.ch>
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
	 * @author Nicolas Karrer <nkarrer@hsr.ch>
	 */
	private function getWeekEnd($timestamp) {
		$diff = (7-date('w', $timestamp))%7;
		return mktime(23, 59, 59, date('n', $timestamp), date('j', $timestamp)+$diff, date('Y', $timestamp));
	}
}
?>