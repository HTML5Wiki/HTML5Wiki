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
	
	const TITLEFIELD_MIN_LENGTH = 2;
	const TITLEFIELD_MAX_LENGTH = 100;
	
	/**
	 * Current user
	 * @var Html5Wiki_Model_User
	 */
	private $user = null;

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


	public function newAction() {
		$parameters = $this->router->getRequest()->getGetParameters();

		if ($this->router->getRequest()->isAjax()) {
			$this->setNoLayout();
			// todo Exception?
		} else {
			$permalink = $this->getPermalink();			
		}
		
		$this->setTemplate('edit.php');
		$this->showArticleEditor(array(
			'title'=>$permalink
			,'content'=>''
			,'author'=>''
			,'tags'=>''
			,'versionComment'=>''
			,'request' => $this->router->getRequest()

		));
		
		/*
	$this->template->assign('title', $preparedData['title']);
	$this->template->assign('content', $preparedData['content']);
	$this->template->assign('author', $preparedData['author']);
	$this->template->assign('tags', $preparedData['tags']);
    $this->template->assign('versionComment', $preparedData['versionComment']);

	$this->template->assign('wikiPage', $preparedData['wikiPage']);
	$this->template->assign('request', $preparedData['request']);
		*/
		//$preparedData = $this->prepareArticleModelForEditor($wikiPage);
		//$this->showArticleEditor($preparedData);
	}



	/**
	 * Handles an edit-request for the given permalink in the url.<br/>
	 * If the permalink was not found, the user gets redirected to the search
	 * page where he can choose to create a fresh article with the permalink
	 * entered.
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
	
		if($wikiPage == null) {
			$this->redirectToArticleNotFoundSearch($permalink);
		} else {
            $preparedData = $this->prepareArticleModelForEditor($wikiPage);
			$this->showArticleEditor($preparedData);
		}
	}
	
	/**
	 * Creates new article. Afterwards it loads the edit page.<br/>
	 * If there was an error during creation of the article, the user gets
	 * redirected to the search page.
	 */
	public function createAction() {
		$parameters	= $this->router->getRequest()->getPostParameters();
		$permalink = $this->getPermalink();
		
		if ($this->router->getRequest()->isAjax()) {
			$this->setNoLayout();
		}
		
		if(($user = $this->getUser($parameters)) !== null) {
			$mediaVersion   = new Html5Wiki_Model_MediaVersion_Table();
			$row = $mediaVersion->createRow(array(
				'permalink' => $permalink
				,'userId' => $user->id
				,'timestamp' => time()
			));
			$row->save();
			
			$articleVersion = new Html5Wiki_Model_ArticleVersion_Table();
			$articleRow = $articleVersion->createRow();
			$articleRow->setFromArray(array('mediaVersionId' => $row->id, 'mediaVersionTimestamp' => $row->timestamp, 'title' => $permalink));
			$articleRow->save();
		
			$this->setTemplate('edit.php');
			
			// reload the article because it needs also the MediaVersion informations.
			$wikiPage = new Html5Wiki_Model_ArticleVersion();
			$wikiPage->loadByIdAndTimestamp($articleRow->mediaVersionId, $articleRow->mediaVersionTimestamp);

            $preparedData = $this->prepareArticleModelForEditor($wikiPage);
			$this->showArticleEditor($preparedData);
		} else {
			$this->redirectToArticleNotFoundSearch($permalink);
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
			$user = $this->getUser($parameters);
			if($user !== false) {
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

                $this->showArticle($wikiPage);
            }
        } else {
            //TODO: go back to the edit page
            //      show error messages
            $user = $this->getUser($parameters);
            if($user !== false) {

                $tags = explode(',', $parameters['tags']); 

                $wrongUpdatedWikiPage = new Html5Wiki_Model_ArticleVersion();
				$wrongUpdatedWikiPage->loadByIdAndTimestamp($oldWikiPage->id, $oldWikiPage->timestamp);
				$wrongUpdatedWikiPage->title = $title;
				$wrongUpdatedWikiPage->content = $parameters['contentEditor'];

                $this->setTemplate('edit.php');
                $preparedData = $this->prepareArticleModelForEditor($wrongUpdatedWikiPage, null, null, $tags);
			    $this->showArticleEditor($preparedData, $error);
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
        $errorMsg = array();
        $errorFields = array(
            'title' => false,
            'content' => false,
            'author' => false,
            'authorEmail' => false,
            'tags' => false,
            'versionComment' => true
        );

        //Test Title
        $validatorChainTitle = new Zend_Validate();
        $validatorChainTitle->addValidator(new Zend_Validate_Alnum(true))
                            ->addValidator(new Zend_Validate_StringLength(self::TITLEFIELD_MIN_LENGTH, self::TITLEFIELD_MAX_LENGTH));

        if (isset($parameters['txtTitle']) && //the title was updated
            !$validatorChainTitle->isValid($parameters['txtTitle'])) {            
            $success = false;

            foreach ($validatorChainTitle->getMessages() as $message) {
                array_push($errorMsg, "Title " . $message);
                $errorFields['title'] = true;
            }
        }

        //Test Content
        $validatorChainContent = new Zend_Validate();
        $validatorChainContent->addValidator(new Zend_Validate_NotEmpty());

        if (!$validatorChainContent->isValid($parameters['contentEditor'])) {
            $success = false;
			
            foreach ($validatorChainContent->getMessages() as $message) {
                array_push($errorMsg, "Content " . $message);
                $errorFields['content'] = true;
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
                array_push($errorMsg, "Tags " . $message);
                $errorFields['tags'] = true;
            }
        }

        //Test VersionComment
        ////Not needed

        $error['messages'] = $errorMsg;
        $error['fields'] = $errorFields;

        return $success;
    }

	/**
	 * Shows an article model in the proper template.
	 *
	 * @param	Html5Wiki_Model_ArticleVersion $wikiPage
	 */
	private function showArticle(Html5Wiki_Model_ArticleVersion $wikiPage) {
		$this->setTemplate('article.php');
		
		$tagRows = $wikiPage->getTags();
		$tags = array();

		foreach ($tagRows as $tag) {
			$tags[] = $tag->tagTag;
		}

		$this->template->assign('wikiPage', $wikiPage);
        $this->template->assign('request', $this->router->getRequest());
		$this->template->assign('markDownParser', new Markdown_Parser());
		$this->template->assign('tags', $tags);
	} 

	/**
	 * Prepares an article-model for using it in the editor page.
	 *
	 * @param $wikiPage model
	 * @param $title
	 * @param $content
	 * @param $tags
	 * @param $versionComment
	 * @return Html5Wiki_Model_ArticleVersion
	 */
    private function prepareArticleModelForEditor(HTML5Wiki_Model_ArticleVersion $wikiPage,
        string $title = null,
        string $content = null,
        array $tags = null,
        string $versionComment = null) {

        $data = array();

        $data['wikiPage'] = $wikiPage;

        if ($title !== null) {
            $data['title'] = $title;
        } else {
            $data['title'] = isset($wikiPage->title) ? $wikiPage->title : '';
        }

        if ($content !== null) {
            $data['content'] = $content;
        } else {
            $data['content'] = isset($wikiPage->content) ? $wikiPage->content : '';
        }

        if ($tags !== null) {
            $data['tags'] = $tags;
        } else {
            $tagRows = $wikiPage->getTags();

            $tags = array();

            foreach ($tagRows as $tag) {
                $tags[] = $tag->tagTag;
            }

            $data['tags'] = $tags;
        }

        if ($versionComment !== null) {
            $data['versionComment'] = $versionComment;
        } else {
            $data['versionComment'] = '';
        }

        //author data from cookies
        $data['author'] = $this->getUser() !== null ? $this->getUser() : new Html5Wiki_Model_User();

        $data['request'] = $this->router->getRequest();

        return $data;
    }
	
	/**
	 * 
	 * @param $wikiPage
	 * @return unknown_type
	 */
	private function showArticleEditor(array $preparedData, array $error = array()) {
		if($this->layoutTemplate != null) {
			$this->layoutTemplate->assign('title', $preparedData['title']);
		}
		$this->template->assign('title', $preparedData['title']);
		$this->template->assign('content', $preparedData['content']);
		$this->template->assign('author', $preparedData['author']);
		$this->template->assign('tags', $preparedData['tags']);
        $this->template->assign('versionComment', $preparedData['versionComment']);

		$this->template->assign('wikiPage', $preparedData['wikiPage']);
		$this->template->assign('request', $preparedData['request']);
        
        $this->template->assign('error', $error);
	}
	
	/**
	 * If an article permalink was not found, this method redirects the user to
	 * the search page and gives the possibility to create a fresh article with
	 * the given permalink.
	 *
	 * @param $permalink
	 */
	private function redirectToArticleNotFoundSearch($permalink) {
		$searchUrl = $this->router->getRequest()->getBasePath()
		           . '/index/search?term='. $permalink
				   . '&newarticle=1';
		$this->router->redirect($searchUrl);
	}
	
	/**
	 * Returns user. When already set as field, use it. Otherwise handle user request according to params.
	 * 
	 * For preventing losing user informations when only doing an ajax request (which does not send cookie-headers).
	 * @param array $parameters
	 * @return Html5Wiki_Model_User 
	 */
	private function getUser(array $parameters = array()) {
		if ($this->user === null && count($parameters)) {
			$this->user = $this->handleUserRequest($parameters);
		}
		return $this->user;
	}
	
	/**
	 * @param array $parameters
	 * @return bool|Html5Wiki_Model_User
	 */
	private function handleUserRequest(array $parameters) {
		if (isset($parameters['hiddenAuthorId']) && $parameters['hiddenAuthorId'] !== '') {
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
		if(isset($userData['id']) && $userData['id'] > 0) {
			return $user;
		} else {
			$userTable = new Html5Wiki_Model_User_Table();
			$existingUser = $userTable->userExists($userData['name'], $userData['email']);
			if (isset($existingUser->id)) {
				return $existingUser;
			}
			if($userData['email'] && $userData['name']) {
				$user = $userTable->createRow(array('email' => $userData['email'], 'name' => $userData['name']));
				$user->save();
				
				return $user;
			}
		}
		
		return null;
	}

	/**
	 * Tries to load an article with the given permalink from the URL.<br/>
	 * If the article was not found, the user will be redirected to the search
	 * with an option to create a new page with the entered permalink/title.
	 *
	 * @see Application_IndexController#searchAction
	 * @see Html5Wiki_Routing_Router#redirect
	 */
	public function readAction() {
 	 	$parameters = $this->router->getRequest()->getGetParameters();
		$permalink = $this->getPermalink();
		
		$this->template->assign('request', $this->router->getRequest()); 
		
		if($this->router->getRequest()->isAjax()) {
			$this->setNoLayout();
			$wikiPage = new Html5Wiki_Model_ArticleVersion();
			$wikiPage->loadLatestById($parameters['idArticle']);
		} else {
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

		if(isset($wikiPage->id)) {
			$this->showArticle($wikiPage);
		} else {
			$this->redirectToArticleNotFoundSearch($permalink);
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
		
		$translatedTitle = $this->template->getTranslate()->_('title');
		$translatedTags   = $this->template->getTranslate()->_('tags');
		
		$leftContent = $translatedTitle . ': ' . $leftVersion->getCommonName() 
						. "\n\n" . $translatedTags . ': ' 
						. $this->getFormattedTags($leftVersion->getTags());
		$leftContent .= "\n\n" . $leftVersion->content;
		
		$rightContent = $translatedTitle . ': ' . $rightVersion->getCommonName() 
						. "\n\n" . $translatedTags . ': ' 
						. $this->getFormattedTags($rightVersion->getTags());
		$rightContent .= "\n\n" . $rightVersion->content;
		
		$diff = new PhpDiff_Diff(explode("\n", $rightContent), explode("\n", $leftContent));
		$this->template->assign('diff', $diff);
		$this->template->assign('leftTimestamp', $left);
		$this->template->assign('rightTimestamp', $right);
		$this->setTitle($leftVersion->getCommonName());
	}
	
	private function getFormattedTags(Zend_Db_Table_Rowset_Abstract $tagset) {
		$tags = array();
		foreach ($tagset as $tag) {
			$tags[] = $tag->__toString();
		}
		
		return implode(", ", $tags);
	}
 }

?>
