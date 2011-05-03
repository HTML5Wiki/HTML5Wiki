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
	 * @override
	 * @param Html5Wiki_Routing_Interface_Router $router
	 */
	public function dispatch(Html5Wiki_Routing_Interface_Router $router) {
		try {
			parent::dispatch($router);
		} catch (Html5Wiki_Exception_404 $e) {
			$this->readAction();
		}
	}

	/**
	 * Edit Article
	 * 
	 * @author	Alexandre Joly <ajoly@hsr.ch>
	 */
	public function editAction() {		
		$parameters = $this->router->getRequest()->getPostParameters();

		if (isset($parameters['ajax'])) {
			$this->setNoLayout();
			$data = array('mediaVersionId' => $parameters['idArticle']);
			$wikiPage = new Html5Wiki_Model_ArticleVersion(array('data'=>$data));
		} else {
			$permalink = $this->getPermalink();
			$data = array('permalink'=>$permalink);
			$wikiPage = new Html5Wiki_Model_ArticleVersion(array('data'=>$data));
		}
	
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
		$parameters	= $this->router->getRequest()->getPostParameters();
		
		if (isset($parameters['ajax'])) {
			$this->setNoLayout();
		}
		
		if($this->handleUserRequest($parameters) != false) {
			$user       = new Html5Wiki_Model_User();
			$wikiPage   = new Html5Wiki_Model_ArticleVersion();
		
			$wikiPage->setData(array('permalink' => $this->getPermalink(), 'title' => $this->getPermalink(), 'userId' => $user->id));
			$wikiPage->save();
		
			$this->setTemplate('edit.php');
			
			$this->loadEditPage($wikiPage);
		} else {
			$this->loadNoArticlePage($this->getPermalink());
		}
	}
	
	/**
	 * Save the edited Article and forward to the Article 
	 * or if the validation failed, to the edit page
	 * 
	 * @author	Alexandre Joly <ajoly@hsr.ch>
	 */
	public function saveAction() {
		$parameters	= $this->router->getRequest()->getPostParameters();
		
		if (isset($parameters['ajax'])) {
			$this->setNoLayout();
		}

		$oldWikiPage = new Html5Wiki_Model_ArticleVersion($parameters['hiddenIdArticle'], $parameters['hiddenTimestampArticle']);
		//TODO: some validation

		$validate = true;

		if ($validate) {
			$user = $this->handleUserRequest($parameters);
			if($user !== false) {

                 //TODO: handle Tag request
				$wikiPage = new Html5Wiki_Model_ArticleVersion();

				$title = isset($parameters['txtTitle']) ? $parameters['txtTitle'] : $oldWikiPage->title;

                //TODO: fix permalink, previousVersion
				$data = array(
					'id'        => $oldWikiPage->id,
					'permalink' => $oldWikiPage->permalink,
					'title'     => $title,
					'content'   => $parameters['contentEditor'],
					'userId'    => $user->id,
				);


				$wikiPage->setData($data);
				$wikiPage->save();

                $this->loadPage($wikiPage);
            }
        } else {
            
        }
	}

	/**
	 * Loads the standard view page for a given article
	 * 
	 * @author	Nicolas Karrer <nkarrer@hsr.ch>
	 * @param	Html5Wiki_Model_ArticleVersion $wikiPage
	 */
	private function loadPage(Html5Wiki_Model_ArticleVersion $wikiPage) {
		$this->setTemplate('article.php');

		$this->template->assign('wikiPage', $wikiPage);
		$this->template->assign('markDownParser', new Markdown_Parser());
	} 
	
	/**
	 * Loads the noarticle page. With a button to add an article with the requested permalink
	 * 
	 * @author	Nicolas Karrer <nkarrer@hsr.ch>
	 * @param	$permalink
	 */
	private function loadNoArticlePage($permalink) {
		$ajax = $this->router->getRequest()->getPost('ajax');		
		
		if ($ajax == true) {
			$this->setNoLayout();
		} 
		
		$this->setTemplate('noarticle.php');
		
		$this->template->assign('request', $this->router->getRequest());		
		$this->template->assign('permalink', $permalink);
		$this->template->assign('author', new Html5Wiki_Model_User());
	}
	
	/**
	 * 
	 * @param $wikiPage
	 * @return unknown_type
	 */
	private function loadEditPage(Html5Wiki_Model_ArticleVersion $wikiPage) {

		//Prepare article data for the view
		$title = $wikiPage->title;
		$content = $wikiPage->content;
		//$tag = $wikiPage->getTags(); 

		//Get author data from cookies
		$author	= new Html5Wiki_Model_User();
		
		if($this->layoutTemplate != null) {
			$this->layoutTemplate->assign('title', $title);
		}
		$this->template->assign('title', $title);
		$this->template->assign('content', $content);
		$this->template->assign('author', $author);
		$this->template->assign('wikiPage', $wikiPage);
		$this->template->assign('request', $this->router->getRequest());
		//$this->template->assign('tag', $tag);
	}
	
	/**
	 * @param array $parameters
	 * @return bool|Html5Wiki_Model_User
	 */
	private function handleUserRequest(array $parameters) {
		$userData	= array(
			'id'        => intval($parameters['hiddenAuthorId']),
			'name'      => $parameters['txtAuthor'],
			'email' 	=> $parameters['txtAuthorEmail']
		);
		
		$user = new Html5Wiki_Model_User(array('data' => $userData));
		if($userData['id'] > 0) {
			return $user;
		} else {
			$userTable = new Html5Wiki_Model_User_Table();
			$existingUser = $userTable->userExists($userData['name'], $userData['email']);
			if (isset($existingUser->id)) {
				return $existingUser;
			}
			if($userData['email'] && $userData['name']) {
				$user->save();
				
				return $user;
			}
		}
		
		return false;
	}

	/**
	 * @return void
	 */
	public function readAction() {
		$parameters = $this->router->getRequest()->getPostParameters();

		if( isset($parameters['ajax']) ) {
			$this->setNoLayout();
			$data = array('mediaVersionId' => $parameters['idArticle']);
			$wikiPage = new Html5Wiki_Model_ArticleVersion(array('data'=>$data));
		} else {
			$permalink = $this->getPermalink();
			if ($permalink === '' && $this->config->routing->defaultController !== 'wiki') {
				throw new Html5Wiki_Exception_404("Empty permalink is not allowed");
			} else if ($permalink === '' && $this->config->routing->defaultController === 'wiki') {
				$permalink = $this->config->routing->defaultController . '/' . $this->config->routing->defaultAction;
			}
				
			$data = array('permalink' => $permalink);
			$wikiPage = new Html5Wiki_Model_ArticleVersion(array('data'=>$data));
			if($wikiPage != null && isset($wikiPage->title)) {
				$this->setTitle($wikiPage->title);
			}
		}

		if(!isset($wikiPage->id)) {
			$this->loadNoArticlePage($permalink);
		} else {
			$this->loadPage($wikiPage);
		}
	}

	/**
	 * @throws Html5Wiki_Exception_404
	 * @author Manuel Alabor <malabor@hsr.ch>
	 */
	public function historyAction() {
		$parameters = $this->router->getRequest()->getPostParameters();
		$mediaManager = new Html5Wiki_Model_MediaVersionManager();
		
		if(isset($parameters['ajax'])) {
			$this->setNoLayout();
			$id = $parameters['idArticle'];
			
			$versions = $mediaManager->getMediaVersionsById($id);
			$latestVersion = $versions->current();
			$groupedVersions = $mediaManager->groupMediaVersionByTimespan($versions);
		} else {
			$permalink = $this->getPermalink();
			
			$versions = $mediaManager->getMediaVersionsByPermalink($permalink);
			$latestVersion = $versions->current();
			$groupedVersions = $mediaManager->groupMediaVersionByTimespan($versions);
		}

		if(count($versions) == 0) {
			throw new Html5Wiki_Exception_404();
		}

		// todo get current wikipage title!

		$this->template->assign('wikiPage', $latestVersion);
		$this->template->assign('versions', $groupedVersions);
	}
 }

?>
