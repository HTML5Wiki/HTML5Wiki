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
	
	private $modelEngines = array();
	
	/**
	 * Creates a new instance of SearchEngine.
	 */
	public function __construct() {
		$this->registerModelEngines();
	}
	
	/**
	 * Register all available model engines to the search engine.
	 */
	private function registerModelEngines() {
		$this->modelEngines = array(
			new Html5Wiki_Search_ArticleModelEngine()
		);
	}
	
	
	/**
	 * Checks a search term against different validation rules.
	 *
	 * @param $term
	 * @return true or an array with validation errors
	 */
	public function termIsValid($term) {
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
		
		$this->prepareBasicSearch($select, $mediaVersionTable, $term);
		$this->prepareTagSearch($select, $term);
		$this->prepareModelEngineSearch($select, $term);
		
		$rawResults = $mediaVersionTable->fetchAll($select);
		$models = $this->createModelsFromRawResults($rawResults);
		
		return $models;
	}
	
	/**
	 * Prepares a Zend_Db_Select-Statement for basic search.
	 *
	 * @param Zend_Db_Select $select Select-instance
	 * @param Html5Wiki_Model_MediaVersion_Table $mediaVersionTable instance
	 * @param $term search term
	 * @return Zend_Db_Select
	 */
	private function prepareBasicSearch(Zend_Db_Select $select, Html5Wiki_Model_MediaVersion_Table $mediaVersionTable, $term) {
		$select->setIntegrityCheck(false);
		$select->from($mediaVersionTable);
		$select->where('state = ?', 'PUBLISHED');
		$select->group('id');
		$select->order('timestamp DESC');
		
		return $select;
	}
	
	/**
	 * Prepares a Zend_Db_Select-Statement for searching against tags.
	 *
	 * @param Zend_Db_Select $select Select-instance
	 * @param $term search term
	 * @return Zend_Db_Select
	 */
	private function prepareTagSearch(Zend_Db_Select $select, $term) {
		$select->orWhere('MediaVersionTag.tagTag LIKE ?', '%' . $term . '%');
		$select->joinLeft('MediaVersionTag',
			'MediaVersion.id = MediaVersionTag.mediaVersionId '
			.'AND MediaVersion.timestamp = MediaVersionTag.mediaVersionTimestamp'
		);
		
		return $select;
	}
	
	/**
	 * Prepares a Zend_Db_Select-Statement for searching against all registred
	 * ModelEngines.
	 *
	 * @param Zend_Db_Select $select Select-instance
	 * @param $term search term
	 * @return Zend_Db_Select
	 */
	private function prepareModelEngineSearch(Zend_Db_select $select, $term) {
		foreach($this->modelEngines as $modelEngine) {
			$select = $modelEngine->prepareSearchStatement($select, $term);
		}
		
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
		
		foreach($this->modelEngines as $modelEngine) {
			if($modelEngine->canPrepareModelForType($type)) {
				$properModel = $modelEngine->prepareModelFromData($data);
				break;
			}
		}
		
		return $properModel;
	}
	
}
?>