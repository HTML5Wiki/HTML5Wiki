<?php
/**
 * Wiki controller
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @package Application
 */
class Application_WikiController extends Html5Wiki_Controller_Abstract {

	/**
	 * 
	 * @return unknown_type
	 */
	public function foobarAction() {
		$foo = array('bar', 'baz');
		$this->template->assign('foo', $foo);
	}

	/**
	 * Edit Article
	 * 
	 * @author	Alexandre Joly <ajoly@hsr.ch>
	 */
	public function editAction() {
		$ajax = $this->router->getRequest()->getPost('ajax');		

		if ($ajax === true) {
			$this->setNoLayout();
		} 
		
		$permalink = $this->getPermalink();

		//Get current Article
		$wikiPage = Html5Wiki_Model_ArticleManager::getArticleByPermaLink($permalink);
	
		if( $wikiPage == null ) {
			$this->loadNoArticlePage($permalink);
		} else {
			$this->loadEditPage($wikiPage);
		}

	}
	
	/**
	 * Creates new article. Afterwards it loads the edit page.
	 * 
	 * @author	Nicolas Karrer <nkarrer@hsr.ch>
	 */
	public function createAction() {
		$wikiPage = new Html5Wiki_Model_Article(0, 0);
		
		$wikiPage->setData(array('permalink' => $this->getPermalink(), 'title' => $this->getPermalink()));
		$wikiPage->save();
		
		$this->setTemplate('edit.php');
		
		$this->loadEditPage($wikiPage);
	}
	
	/**
	 * Save the edited Article and forward to the Article 
	 * or if the validation failed, to the edit page
	 * 
	 * @author	Alexandre Joly <ajoly@hsr.ch>
	 */
	public function saveAction() {
		echo "saving...";
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
			
			//@todo replace this with an aporpriate index page
			if( !( strlen($permalink) > 0 ) ) throw new Html5Wiki_Exception_404();
			
			$wikiPage	= Html5Wiki_Model_ArticleManager::getArticleByPermaLink($permalink);
			
			if( $wikiPage == null ) {
				$this->loadNoArticlePage($permalink);
			} else {
				$this->loadPage($wikiPage);
			}
		}
	}

	/**
	 * Get permalink from url
	 *
	 * Works like this:
	 * User requests /wiki/foobar
	 * -> Method returns foobar, because the Action foobar doesn't exist.
	 * User requests /wiki/edit/foobar
	 * -> Method returns also foobar -> it knows that the action edit exists, so it adds this to the
	 *    needle of the substring replacement.
	 *
	 * @return string
	 */
	private function getPermalink() {
		$uri = $this->router->getRequest()->getUri();
		$basePath = $this->router->getRequest()->getBasePath();
		$basePath .= '/';
		
		$needle = $basePath . $this->router->getController() . '/';
		$needle .= method_exists($this, $this->router->getAction() . 'Action') ? $this->router->getAction() . '/' : '';
		
		$permalink = substr_replace($uri, '', strpos($uri, $needle), strlen($needle));
		
		return $permalink;
	}
	
	/**
	 * Loads the standard view page for a given article
	 * 
	 * @author	Nicolas Karrer <nkarrer@hsr.ch>
	 * @param	Html5Wiki_Model_Article $wikiPage
	 */
	private function loadPage(Html5Wiki_Model_Article $wikiPage) {
		$this->setTemplate('article.php');
				
		$this->setTitle($wikiPage->title);

		$markDownParser = new Markdown_Parser();
		$this->template->assign('title', $wikiPage->title);
		$this->template->assign('content', $markDownParser->transform($wikiPage->content));
	} 
	
	/**
	 * Loads the noarticle page. With a button to add an article with the requested permalink
	 * 
	 * @author	Nicolas Karrer <nkarrer@hsr.ch>
	 * @param	$permalink
	 */
	private function loadNoArticlePage($permalink) {
		$this->setTemplate('noarticle.php');
		
		$this->template->assign('request', $this->router->getRequest());		
		$this->template->assign('permalink', $permalink);
	}
	
	/**
	 * 
	 * @param $wikiPage
	 * @return unknown_type
	 */
	private function loadEditPage(Html5Wiki_Model_Article $wikiPage) {
		
		//Prepare article data for the view
		$title = $wikiPage->title;
		$content = $wikiPage->content;
		//$tag = $wikiPage->getTags(); 

		//Get author data from cookies
		//$author	= new Html5Wiki_Model_User(0);
		
		$this->layoutTemplate->assign('title', $title);
		$this->template->assign('title', $title);
		$this->template->assign('content', $content);
		//$this->template->assign('author', $author);
		//$this->template->assign('tag', $tag);
	}
 }

?>
