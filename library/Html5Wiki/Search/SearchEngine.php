<?php
/**
 * This file is part of the HTML5Wiki Project.
 * 
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
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
 * SearchEngine
 * Encapsulates the search on versioned data on the database.
 */
class Html5Wiki_Search_SearchEngine {
	
	/**
	 * Registered engine plugins
	 * 
	 * @var array
	 */
	private $enginePlugins = array();
	
	/**
	 * Creates a new instance of SearchEngine.
	 */
	public function __construct() {
		$this->registerEnginePlugins();
	}
	
	/**
	 * Register all available model engines to the search engine.
	 */
	private function registerEnginePlugins() {
		$this->enginePlugins = array(
			new Html5Wiki_Search_EnginePlugin_Article()
			,new Html5Wiki_Search_EnginePlugin_Tag()
		);
	}
	
	
	/**
	 * Checks a search term against different validation rules.
	 *
	 * @param $term
	 * @return true or an array with validation errors
	 */
	public function validateTerm($term) {
		$validatorChain = new Zend_Validate();
		$validatorChain->addValidator(new Zend_Validate_Regex('#[a-z0-9\./,-\\\]#i'));
		$validatorChain->addValidator(new Zend_Validate_StringLength(array('min' => 1, 'encoding' => 'UTF-8')));
		
		if ($validatorChain->isValid($term)) {
			return true;
		}
		return $validatorChain->getMessages();
	}
	
	
	/**
	 * Searchs for a given term $term using all registred ModelEngines.<br/>
	 * Returns an array of matched results using the results specific model
	 * class.
	 *
	 * @param $term search term
	 * @return array
	 */
	public function search($term) {
		$mediaVersionTable = new Html5Wiki_Model_MediaVersion_Table();
		$select = $mediaVersionTable->select();
		
		$this->prepareSelect($select, $mediaVersionTable, $term);
		$this->prepareEnginePluginsSearch($select, $term);
		$this->prepareBasicSearch($select, $term);
		
		$rawModels = $mediaVersionTable->fetchAll($select);
		$results = $this->createResultList($rawModels, $term);
		
		return $results;
	}
	
	/**
	 * Creates a Zend_Db_Select-instance with initial values.
	 *
	 * @param Zend_Db_Select $select Select-instance
	 * @param Html5Wiki_Model_MediaVersion_Table $mediaVersionTable instance
	 * @param $term search term
	 * @return Zend_Db_Select
	 */
	private function prepareSelect(Zend_Db_Select $select, Html5Wiki_Model_MediaVersion_Table $mediaVersionTable, $term) {
		$select->setIntegrityCheck(false);
		$select->from($mediaVersionTable);
		
		return $select;
	}
	
	/**
	 * Prepares a Zend_Db_Select-Statement for searching against all registred
	 * EnginePlugins.
	 *
	 * @param Zend_Db_Select $select Select-instance
	 * @param $term search term
	 * @return Zend_Db_Select
	 */
	private function prepareEnginePluginsSearch(Zend_Db_select $select, $term) {
		foreach($this->enginePlugins as $enginePlugin) {
			$select = $enginePlugin->prepareSearchStatement($select, $term);
		}
		
		return $select;
	}
	
	/**
	 * Prepares a Zend_Db_Select-Statement for basic search.
	 *
	 * @param Zend_Db_Select $select Select-instance
	 * @param Html5Wiki_Model_MediaVersion_Table $mediaVersionTable instance
	 * @param $term search term
	 * @return Zend_Db_Select
	 */
	private function prepareBasicSearch(Zend_Db_Select $select, $term) {
		/* The actual sql statement:
		 *
		 * SELECT data.* FROM MediaVersion data
		 *   JOIN (SELECT id, MAX(timestamp) as timestamp FROM MediaVersion GROUP BY id) latest
		 *     ON latest.id = data.id
		 *    AND data.timestamp = latest.timestamp
		 */
		$mediaVersionTable = new Html5Wiki_Model_MediaVersion_Table();
		$joinSelect = $mediaVersionTable->select();
		$joinSelect->from(
			$mediaVersionTable
			,array(
				'id as latestId'
				,'MAX(timestamp) as latestTimestamp'
			)
		);
		$joinSelect->group('latestId');
		
		
		$select->join($joinSelect, 't.latestId = id AND timestamp = t.latestTimestamp');
		$select->group('MediaVersion.id');
		$select->having('state = ?', Html5Wiki_Model_MediaVersion_Table::getState('PUBLISHED'));
		$select->order('MediaVersion.timestamp DESC');
		
		return $select;
	}
	
	/**
	 * Returns an array with results for each matched raw model.
	 *
	 * @param $rawResults array with "raw" Html5Wiki_Model_MediaVersion's
	 * @param $term search term used for search
	 * @return array with results
	 */
	private function createResultList($rawResults, $term) {
		$results = array();
		foreach($rawResults as $raw) {
			$result = $this->createResult($raw, $raw->mediaVersionType, $term);
			if($result != '') $results[] = $result;
		}
		
		return $results;
	}
	
	/**
	 * Returns an array with a properly populated model and an array with the
	 * places, where the search term matched.
	 *
	 * @param Html5Wiki_Model_MediaVersion $rawModel
	 * @param $type type of result -> see MediaVersion.mediaVersionType on DB
	 * @param $term the search term used
	 * @return array or FALSE, if no fitting ModelEngine was found
	 */
	private function createResult(Html5Wiki_Model_MediaVersion $rawModel, $type, $term) {
		$result = FALSE;
		$properModel = $this->createProperModel($rawModel, $type);
		
		if($properModel !== FALSE) {
			$matchOrigins = $this->createMatchOrigins($properModel, $term);
			$result = array(
				'model' => $properModel
				,'matchOrigins' => $matchOrigins
			);
		}
		
		return $result;
	}
	
	/**
	 * Creates a specific Model instance out of a "raw" Html5Wiki_Model_MediaVersion
	 *
	 * @param $rawModel Html5Wiki_Model_MediaVersion
	 * @param $type type of the "raw" model (see mediaVersionType enum on DB)
	 * @return model instance or FALSE, if type was not able to handled by plugins
	 */
	private function createProperModel(Html5Wiki_Model_MediaVersion $rawModel, $type) {
		$properModel = FALSE;
		
		foreach($this->enginePlugins as $enginePlugin) {
			if($enginePlugin->canPrepareModelForType($type)) {
				$data = $rawModel->toArray();
				$properModel = $enginePlugin->prepareModelFromData($data);
				break;
			}
		}
		
		return $properModel;
	}
	
	/**
	 * Asks all EnginePlugins, where the searchterm $term matched the model
	 * $model.
	 *
	 * @param $model Html5Wiki_Model_MediaVersion-instance/child
	 * @param $term search term
	 * @return array
	 */
	private function createMatchOrigins($model, $term) {
		$matchOrigins = array();
		foreach($this->enginePlugins as $enginePlugin) {
			$matchOrigins = array_merge(
				$matchOrigins,
				$enginePlugin->getMatchOrigins($term, $model)
			);
		}
		
		return $matchOrigins;
	}
	
}
?>