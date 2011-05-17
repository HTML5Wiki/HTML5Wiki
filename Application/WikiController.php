<?php
/**
 * This file is part of the HTML5Wiki Project.
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright Html5Wiki 2011
 * @package Html5Wiki
 * @subpackage Application
 */

/**
 * Wiki controller
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
		$parameters = $this->router->getRequest()->getGetParameters();

		if ($this->router->getRequest()->isAjax()) {
			$this->setNoLayout();
			$wikiPage = new Html5Wiki_Model_ArticleVersion();
			$wikiPage->loadLatestById($parameters['idArticle']);
		} else {
			$permalink = $this->getPermalink();
			$wikiPage = new Html5Wiki_Model_ArticleVersion();
			$wikiPage->loadLatestByPermalink($permalink);
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
		
		if ($this->router->getRequest()->isAjax()) {
			$this->setNoLayout();
		}
		
		if($this->handleUserRequest($parameters) != false) {
			$user       = new Html5Wiki_Model_User();
			$mediaVersion   = new Html5Wiki_Model_MediaVersion_Table();
			$row = $mediaVersion->createRow();
			$row->setFromArray(array('permalink' => $this->getPermalink(), 'userId' => $user->id, 'timestamp' => time()));
			$row->save();
			
			$articleVersion = new Html5Wiki_Model_ArticleVersion_Table();
			$articleRow = $articleVersion->createRow();
			$articleRow->setFromArray(array('mediaVersionId' => $row->id, 'mediaVersionTimestamp' => $row->timestamp, 'title' => $this->getPermalink()));
			$articleRow->save();
		
			$this->setTemplate('edit.php');
			
			// reload the wikipage because it needs also the MediaVersion informations.
			$wikiPage = new Html5Wiki_Model_ArticleVersion();
			$wikiPage->loadByIdAndTimestamp($articleRow->mediaVersionId, $articleRow->mediaVersionTimestamp);
			
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
		
		if ($this->router->getRequest()->isAjax()) {
			$this->setNoLayout();
		}
		
		$oldWikiPage = new Html5Wiki_Model_ArticleVersion();
		$oldWikiPage->loadByIdAndTimestamp($parameters['hiddenIdArticle'], $parameters['hiddenTimestampArticle']);

        $title = isset($parameters['txtTitle']) ? $parameters['txtTitle'] : $oldWikiPage->title;

        $error = array();

		if ($this->validateArticleEditForm($parameters, $error)) {
			$user = $this->handleUserRequest($parameters);
			if($user !== false) {
                 //TODO: handle Tag request
				$wikiPage = new Html5Wiki_Model_MediaVersion_Table();
				$mediaVersionRow = $wikiPage->createRow(array(
					'id' => $oldWikiPage->id,
					'timestamp' => time(),
					'permalink' => $oldWikiPage->permalink,
					'userId' => $user->id,
					'versionComment' => $parameters['versionComment']
				));
				$mediaVersionRow->save();

				$articleVersion = new Html5Wiki_Model_ArticleVersion_Table();
				$articleVersionRow = $articleVersion->createRow(array(
					'mediaVersionId' => $oldWikiPage->id,
					'mediaVersionTimestamp' => $mediaVersionRow->timestamp,
					'title' => $title,
					'content' => $parameters['contentEditor']
				));
				$articleVersionRow->save();
				
				$tags = explode(',', $parameters['tags']);
				$this->saveTags($tags, $mediaVersionRow->id, $mediaVersionRow->timestamp);
				
				// reload the wikipage because it needs also the MediaVersion informations.
				$wikiPage = new Html5Wiki_Model_ArticleVersion();
				$wikiPage->loadByIdAndTimestamp($articleVersionRow->mediaVersionId, $articleVersionRow->mediaVersionTimestamp);

                $this->loadPage($wikiPage);
            }
        } else {
            //TODO: go back to the edit page
            //      show error messages
            $user = $this->handleUserRequest($parameters);
            if($user !== false) {

                $wrongUpdatedWikiPage = new Html5Wiki_Model_ArticleVersion();
				$wrongUpdatedWikiPage->loadByIdAndTimestamp($oldWikiPage->id, $oldWikiPage->timestamp);
				$wrongUpdatedWikiPage->title = $title;
				$wrongUpdatedWikiPage->content = $parameters['contentEditor'];

                $this->setTemplate('edit.php');
                $this->loadEditPage($wrongUpdatedWikiPage, $error);
            }
        }
	}
	
	private function saveTags(array $tags, $mediaVersionId, $mediaVersionTimestamp) {
		foreach($tags as $tag) {
			$tagRow = new Html5Wiki_Model_Tag();
			$tagRow->loadByTag($tag);
			if (!isset($tagRow->tag)) {
				$tagRow = new Html5Wiki_Model_MediaVersion_Mediatag_Tag_Table();
				$tagRow = $tagRow->createRow(array(
					'tag' => $tag
				));
				$tagRow->save();
			}
			
			$mediaTag = new Html5Wiki_Model_MediaVersion_Mediatag_Table();
			$mediaTagRow = $mediaTag->createRow(array(
				'tagTag' => $tag,
				'mediaVersionId' => $mediaVersionId,
				'mediaVersionTimestamp' => $mediaVersionTimestamp
			));
			$mediaTagRow->save();
		}
	}

    /**
     * Validate the form data after saving the article
     *
     * @param  $form
     * @return bool
     * @author Alexandre Joly <ajoly@hsr.ch>
     *
     */
    private function validateArticleEditForm(array $parameters, array &$error) {
        
        $success = true;

        //Test Title
        $validatorChainTitle = new Zend_Validate();
        $validatorChainTitle->addValidator(new Zend_Validate_Alnum(true))
                            ->addValidator(new Zend_Validate_StringLength(5, 25));

        if (isset($parameters['txtTitle']) && //the title was updated
            !$validatorChainTitle->isValid($parameters['txtTitle'])) {            
            $success = false;

            foreach ($validatorChainTitle->getMessages() as $message) {
                array_push($error, "Title " . $message);
            }
        }

        //Test Content
        $validatorChainContent = new Zend_Validate();
        $validatorChainContent->addValidator(new Zend_Validate_NotEmpty());

        if (!$validatorChainContent->isValid($parameters['contentEditor'])) {
            $success = false;
			
            foreach ($validatorChainContent->getMessages() as $message) {
                array_push($error, "Content " . $message);
            }
        }

        //Test User
        ////is it really necessary?? -> handleUserRequest

        //Test Tag
        $validatorChainTags = new Zend_Validate();
        $validatorChainTags->addValidator(new Zend_Validate_NotEmpty());

        if (!$validatorChainTags->isValid($parameters['tags'])) {
            $success = false;
			
            foreach ($validatorChainTags->getMessages() as $message) {
                array_push($error, "Tags " . $message);
            }
        }

        //Test VersionComment
        ////Not needed

        return $success;
    }

	/**
	 * Loads the standard view page for a given article
	 * 
	 * @author	Nicolas Karrer <nkarrer@hsr.ch>
	 * @param	Html5Wiki_Model_ArticleVersion $wikiPage
	 */
	private function loadPage(Html5Wiki_Model_ArticleVersion $wikiPage) {
		$this->setTemplate('article.php');
		
		$tagRows = $wikiPage->getTags();
		$tags = array();

		foreach ($tagRows as $tag) {
			$tags[] = $tag->tagTag;
		}

		$this->template->assign('wikiPage', $wikiPage);
		$this->template->assign('markDownParser', new Markdown_Parser());
		$this->template->assign('tags', $tags);
	} 
	
	/**
	 * Loads the noarticle page. With a button to add an article with the requested permalink
	 * 
	 * @author	Nicolas Karrer <nkarrer@hsr.ch>
	 * @param	$permalink
	 */
	private function loadNoArticlePage($permalink) {
		if ($this->router->getRequest()->isAjax()) {
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
	private function loadEditPage(Html5Wiki_Model_ArticleVersion $wikiPage, array $error = array()) {
		//Prepare article data for the view
		$title = isset($wikiPage->title) ? $wikiPage->title : '';
		$content = isset($wikiPage->content) ? $wikiPage->content : '';
		$tagRows = $wikiPage->getTags();
		
		$tags = array();

		foreach ($tagRows as $tag) {
			$tags[] = $tag->tagTag;
		}

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
		$this->template->assign('tags', $tags);
        
        $this->template->assign('error', $error);
	}
	
	/**
	 * @param array $parameters
	 * @return bool|Html5Wiki_Model_User
	 */
	private function handleUserRequest(array $parameters) {
		if (isset($parameters['hiddenAuthorId'])) {
            $userData	= array(
                'id'        => intval($parameters['hiddenAuthorId']),
                'name'      => $parameters['txtAuthor'],
                'email' 	=> $parameters['txtAuthorEmail']
		    );
        } else {
            $userData	= array(
                'name'      => $parameters['txtAuthor'],
                'email' 	=> $parameters['txtAuthorEmail']
            );
        }

		
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
		$parameters = $this->router->getRequest()->getGetParameters();

		if($this->router->getRequest()->isAjax()) {
			$this->setNoLayout();
			$wikiPage = new Html5Wiki_Model_ArticleVersion();
			$wikiPage->loadLatestById($parameters['idArticle']);
		} else {
			$permalink = $this->getPermalink();
			if ($permalink === '' && $this->config->routing->defaultController !== 'wiki') {
				throw new Html5Wiki_Exception_404("Empty permalink is not allowed");
			} else if ($permalink === '' && $this->config->routing->defaultController === 'wiki') {
				$permalink = $this->config->routing->defaultAction;
			}
				
			$data = array('permalink' => $permalink);
			$wikiPage = new Html5Wiki_Model_ArticleVersion();
			$wikiPage->loadLatestByPermalink($permalink);
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
		$parameters = $this->router->getRequest()->getGetParameters();
		$mediaManager = new Html5Wiki_Model_MediaVersionManager();
		
		if($this->router->getRequest()->isAjax()) {
			$this->setNoLayout();
			$id = $parameters['idArticle'];
			$versions = $mediaManager->getMediaVersionsById($id);
		} else {
			$permalink = $this->getPermalink();
			$versions = $mediaManager->getMediaVersionsByPermalink($permalink);
		}

		if(count($versions) == 0) {
			throw new Html5Wiki_Exception_404();
		}
		
		$latestVersion = new Html5Wiki_Model_ArticleVersion();
		$latestVersion->loadLatestById($versions->current()->id);
		$groupedVersions = $mediaManager->groupMediaVersionByTimespan($versions);
		
		$this->setTitle($latestVersion->title);
		$this->template->assign('wikiPage', $latestVersion);
		$this->template->assign('versions', $groupedVersions);
	}
	
	public function diffAction() {
		$request = $this->router->getRequest();
		$left = $request->getGet('left');
		$right = $request->getGet('right');
		$permalink = $this->getPermalink();
		
		if (!$left || !$right) {
			throw new Html5Wiki_Exception("Left or right must be supplied. TODO: Redirect to history.");
		}
		
		$mediaManager = new Html5Wiki_Model_MediaVersionManager();
		$versions = $mediaManager->getMediaVersionsByPermalinkAndTimestamps($permalink, array($left, $right));
		
		$leftVersion = null;
		$rightVersion = null;
		
		foreach($versions as $version) {
			$articleVersion = new Html5Wiki_Model_ArticleVersion();
			$articleVersion->loadByIdAndTimestamp($version->id, $version->timestamp);
			if ($articleVersion->timestamp === $left) {
				$leftVersion = $articleVersion;
			} else {
				$rightVersion = $articleVersion;
			}
		}
		
		$diff = new PhpDiff_Diff(explode("\n", $rightVersion->content), explode("\n", $leftVersion->content));
		$this->template->assign('diff', $diff);
	}
 }

?>
