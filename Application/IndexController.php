<?php
/**
 * Index Controller for the overall history page
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @package Application
 */
class Application_IndexController extends Html5Wiki_Controller_Abstract {
	
    public function historyAction() {
		$articleTable = new Html5Wiki_Model_ArticleVersion_Table();
		$changes = $articleTable->fetchLatestArticles();
		
		// todo fetch latest changes from mediaversion, not articleversion
		
		$mediaManager = new Html5Wiki_Model_MediaVersionManager();
		$groupedChanges = $mediaManager->groupMediaVersionByTimespan($changes);
		
		$this->template->assign('latestChanges', $groupedChanges);
		$this->layoutTemplate->assign('title', $this->layoutTemplate->getTranslate()->_('recentChanges'));
	}
	
	public function searchAction() {
		if ($this->router->getRequest()->isAjax()) {
			$this->template = new Html5Wiki_Template_Json();
		}
		
		$request = $this->router->getRequest();
		$searchEngine = new Html5Wiki_Search_SearchEngine();
		$term = urldecode($request->getGet('term'));
		$mediaTypes = explode(',',$request->getGet('mediatype'));
		$showCreateNewArticle = $request->getGet('newarticle');
		
		$errors = $searchEngine->validateTerm($term);
		if (is_array($errors)) {
			$this->template->assign('errors', $errors);
			return;
		}
		
		if($showCreateNewArticle === '1') $showCreateNewArticle = TRUE;
		else $showCreateNewArticle = FALSE;
		
		$results = $searchEngine->search($term);
		
		if ($this->router->getRequest()->isAjax()) {
			$this->template->assign('results', $this->prepareAjaxSearchResults($results));
		} else {
			$this->template->assign('results', $results);
			$this->template->assign('markDownParser', new Markdown_Parser());
			$this->template->assign('term', $term);
			$this->template->assign('showCreateNewArticle', $showCreateNewArticle);
			$this->layoutTemplate->assign('title', sprintf($this->layoutTemplate->getTranslate()->_('searchResultsFor'), $term));
		}
	}
	
	/**
	 * Prepares an array with search results (model plus matchorigins) to pass
	 * it as a JSON array trough AJAX.
	 *
	 * @param $results searchresults
	 * @return prepared array
	 */
	private function prepareAjaxSearchResults(array $results) {
		$preparedResults = array();
		
		foreach ($results as $result) {
			$preparedResults[] = array(
				'title' => $result['model']->getCommonName()
				,'mediaType' => strtolower($result['model']->mediaVersionType)
				,'matchOrigins' => $result['matchOrigins']
				,'url'  => $this->router->getRequest()->getBasePath(). '/wiki/' . $result['model']->permalink
			);
		}
		
		return $preparedResults;
	}
}
?>
