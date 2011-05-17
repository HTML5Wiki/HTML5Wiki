<?php
/**
 * SearchEngine
 * Encapsulates the search on versioned data on the database.
 *
 * @author Manuel Alabor <malabor@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @package Library
 * @subpackage Search
 */
class Html5Wiki_Search_SearchEngine {
	
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
	public function isTermValid($term) {
		$validatorChain = new Zend_Validate();
		$validatorChain->addValidator(new Zend_Validate_Alnum(true));
		$validatorChain->addValidator(new Zend_Validate_StringLength(array('min' => 1, 'encoding' => 'UTF-8')));
		
		if ($validatorChain->isValid($term)) {
			return true;
		}
		return $validatorChain->getErrors();
	}
	
	
	/**
	 * Searchs for a given term $term using all registred ModelEngines.<br/>
	 * Returns an array of matched results using the results specific model
	 * class.
	 *
	 * @param $term search term
	 * @return array with Html5Wiki_Model_MediaVersion instances
	 */
	public function search($term) {
		$mediaVersionTable = new Html5Wiki_Model_MediaVersion_Table();
		$select = $mediaVersionTable->select();
		
		$this->prepareSelect($select, $mediaVersionTable, $term);
		$this->prepareEnginePluginsSearch($select, $term);
		$this->prepareBasicSearch($select, $term);
		
		$rawResults = $mediaVersionTable->fetchAll($select);
		$models = $this->createModelsFromRawResults($rawResults);
		
		return $models;
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
		$select->where('state = ?', 'PUBLISHED');
		$select->group('id');
		$select->order('timestamp DESC');
		
		return $select;
	}
	
	/**
	 * Creates an array of models out of the "raw" search results.
	 * 
	 * @param $rawResults
	 * @return array
	 */
	private function createModelsFromRawResults($rawResults) {
		$properModels = array();
		foreach($rawResults as $raw) {
			$properModel = $this->createProperModel($raw, $raw->mediaVersionType);
			if($properModel != '') $properModels[] = $properModel;
		}
		
		return $properModels;
	}
	
	/**
	 * Takes a "raw" search result and picks its specific ModelEngine to convert
	 * it into a proper model.
	 *
	 * @param Html5Wiki_Model_MediaVersion $rawModel
	 * @param $type type of result -> see MediaVersion.mediaVersionType on DB
	 * @return model or FALSE, if no fitting ModelEngine was found
	 */
	private function createProperModel(Html5Wiki_Model_MediaVersion $rawModel, $type) {
		$properModel = FALSE;
		$data = $rawModel->toArray();
		
		foreach($this->enginePlugins as $enginePlugin) {
			if($enginePlugin->canPrepareModelForType($type)) {
				$properModel = $enginePlugin->prepareModelFromData($data);
				break;
			}
		}
		
		return $properModel;
	}
	
}
?>