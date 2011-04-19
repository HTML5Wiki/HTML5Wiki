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
		
		
		//Get current Article
		
		//TODO
		/*
		$article = new Html5Wiki_Model_Article();
		$wikiPage = $article->fetchArticleVersionByPermalink($permalink);
		*/
		
		//Prepare article data for the view
		
		//TODO
		
		$title = 'ze Title';
		$content = 'ze mega content. blablablabla';
		$tag = 'content,mega,bla';
		
		$this->template->assign('title', $title);
		$this->template->assign('content', $content);
		$this->template->assign('tag', $tag);
		
	}

	/**
	 * @override
	 * @param Html5Wiki_Routing_Interface_Router $router
	 */
	public function dispatch(Html5Wiki_Routing_Interface_Router $router) {
		try {
			parent::dispatch($router);
		} catch (Html5Wiki_Exception_404 $e) {
			$this->setTemplate('article.php');

			$permalink = $this->getPermalink();

			$article = new Html5Wiki_Model_Media_Table();
			$wikiPage = $article->fetchArticleVersionByPermalink($permalink);

			if ($wikiPage === null) {
				throw new Html5Wiki_Exception_404('Wikipage "' . $permalink . '" not found.');
			}

			$this->setTitle($wikiPage->title);

			$markDownParser = new Markdown_Parser();
			$this->template->assign('title', $wikiPage->title);
			$this->template->assign('content', $markDownParser->transform($wikiPage->content));
		}
	}
	
	private function getPermalink() {
		$uri = $this->router->getRequest()->getUri();
		
		$needle = '/' . $this->router->getController() . '/';
		$needle .= method_exists($this, $this->router->getAction() . 'Action') ? $this->router->getAction() . '/' : '';
		
		$permalink = substr_replace($uri, '', strpos($uri, $needle), strlen($needle));
		
		return $permalink;
	}
	
}

?>
