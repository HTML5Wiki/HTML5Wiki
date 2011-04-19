<?php
/**
 * Wiki controller
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @package Application
 */
class Application_WikiController extends Html5Wiki_Controller_Abstract {

	public function foobarAction() {
		$foo = array('bar', 'baz');
		$this->template->assign('foo', $foo);
	}

	public function editAction() {
		$ajax = $this->router->getRequest()->getPost('ajax');		
		if ($ajax === true) {
			$this->setNoLayout();
		} 
		
		$permalink = $this->getPermalink();
		
		/*
		$article = new Html5Wiki_Model_Article();
		$wikiPage = $article->fetchArticleVersionByPermalink($permalink);
		*/
		
		$title = 'ze Title';
		$permalinkFull = 'http://www.html5wiki.org/wiki/' . strtolower($permalink);
		$content = $permalink;
		
		$this->template->assign('title', $title);
		$this->template->assign('permalink', $permalinkFull);
		$this->template->assign('content', $content);
		
	}

	/**
	 * @override
	 * @param Html5Wiki_Routing_Interface_Router $router
	 */
	public function dispatch(Html5Wiki_Routing_Interface_Router $router) {
		try {
			parent::dispatch($router);
		} catch (Html5Wiki_Exception_404 $e) {
			$permalink = $this->getPermalink();

			$wikiPage	= Html5Wiki_Model_ArticleManager::getArticleByPermaLink($permalink);
			
			if( $wikiPage == null ) {
				$this->loadNoArticlePage($permalink);
			} else {
				$this->loadPage($wikiPage);
			}
		}
	}
	
	private function getPermalink() {
		$uri = $this->router->getRequest()->getUri();
		$permalinks = explode('/', $uri, 4);
		return $permalinks[3];
	}
	
	private function loadPage(Html5Wiki_Model_Article $wikiPage) {
		$this->setTemplate('article.php');
				
		$this->setTitle($wikiPage->title);

		$markDownParser = new Markdown_Parser();
		$this->template->assign('title', $wikiPage->title);
		$this->template->assign('content', $markDownParser->transform($wikiPage->content));
	} 
	
	private function loadNoArticlePage($permalink) {
		$this->setTemplate('noarticle.php');
				
		$this->template->assign('permalink', $permalink);
	}
}

?>
