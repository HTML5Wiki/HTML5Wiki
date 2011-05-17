<?php
/**
 * SearchEngine
 *
 * @author Manuel Alabor <malabor@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @package Library
 * @subpackage SearchEngine
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
		$modelEngines = array(
			new Html5Wiki_Search_ArticleModelEngine()
		);
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
		$results = array();
		
		return $results;
	}
	
}
?>