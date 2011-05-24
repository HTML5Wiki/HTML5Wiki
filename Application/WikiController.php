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
	 * Minimum length of the title
	 * @var int
	 */
	const TITLEFIELD_MIN_LENGTH = 2;
	
	/**
	 * Maximum length of the title
	 * @var int
	 */
	const TITLEFIELD_MAX_LENGTH = 200;
	
	/**
	 * Minimum length of username
	 * @var int
	 */
	const USERNAME_MIN_LENGTH = 2;
	
	/**
	 * Maximum length of username
	 * @var int
	 */
	const USERNAME_MAX_LENGTH = 255;
	
	/**
	 * Current user
	 * @var Html5Wiki_Model_User
	 */
	private $user = null;

	/**
	 * Override the default dispatching.
	 * When no valid action method has been found, call the readAction.
	 * 
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
	 * Action for reading a article. 
	 * 
	 * If no article could be found for the current URL, redirect to /index/search.
	 */
	public function readAction() {
		$permalink = $this->checkAndGetPermalink();
		
		if ($this->router->getRequest()->isAjax()) {
			$this->setNoLayout();
			
			$article = new Html5Wiki_Model_ArticleVersion();
			$article->loadLatestById($this->router->getRequest()->getGet('idArticle'));
		} else {
			$article = new Html5Wiki_Model_ArticleVersion();
			$article->loadLatestByPermalink($permalink);
		}
		
		if(isset($article->title)) {
			$this->setPageTitle($article->title);
		}
		if(isset($article->id)) {
			$this->showArticle($article);
		} else {
			$this->redirectToArticleNotFoundSearch($permalink);
		}
	}
	
	/**
	 * Shows an article model in the proper template.
	 *
	 * @param Html5Wiki_Model_ArticleVersion $article
	 */
	private function showArticle(Html5Wiki_Model_ArticleVersion $article) {
		$this->setTemplate('read.php');

		$this->template->assign('article', $article);
        $this->template->assign('request', $this->router->getRequest());
		$this->template->assign('markDownParser', new Markdown_Parser());
		$this->template->assign('tags', $article->getTags());
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
		$this->redirect($searchUrl);
	}
	
	/**
	 * Checks if the permalink is valid:
	 * 
	 *   - if the permalink is empty and the defaultController isn't the current controller, throw a 404 exception.
	 *   - if the defaultController is the current controller, return the default action
	 *   - otherwise return permalink found in url
	 * 
	 * @throws Html5Wiki_Exception_404
	 * @return string
	 */
	private function checkAndGetPermalink() {
		$permalink = $this->getPermalink();
		if ($permalink === '' && $this->config->routing->defaultController !== 'wiki') {
			throw new Html5Wiki_Exception_404("Empty permalink is not allowed");
		} else if ($permalink === '' && $this->config->routing->defaultController === 'wiki') {
			return $this->config->routing->defaultAction;
		}
		
		return $permalink;
	}
	
	/**
	 * Create a new article page
	 */
	public function newAction() {
		$permalink = $this->getPermalink();

		$this->showArticleEditor($this->prepareData(null, array('permalink' => $permalink)));
	}
	
	/**
	 * Returns an array containing the contents of given article and data. 
	 * The Data-array takes precedence over article data.
	 * 
	 * @see Application_WikiController#getArticleColumn
	 * 
	 * @param Html5Wiki_Model_ArticleVersion $article
	 * @param array $data
	 * @return array 
	 */
	private function prepareData(Html5Wiki_Model_ArticleVersion $article = null, array $data = array()) {
		return array(
			'mediaVersionId'        => $this->getArticleColumn('mediaVersionId', $article, $data),
			'mediaVersionTimestamp' => $this->getArticleColumn('mediaVersionTimestamp', $article, $data),
			'title'          => $this->getArticleColumn('title', $article, $data),
			'content'        => $this->getArticleColumn('content', $article, $data),
			'userId'         => $this->getArticleColumn('userId', $article, $data),
			'tags'           => $this->getArticleColumn('tags', $article, $data),
			'versionComment' => $this->getArticleColumn('versionComment', $article, $data),
			'permalink'      => $this->getArticleColumn('permalink', $article, $data)
		);
	}
	
	/**
	 * Gets column data from either the article-param or the data param. The data param takes precedence.
	 * @param string $columnName
	 * @param Html5Wiki_Model_ArticleVersion $article
	 * @param array $data
	 * @return string 
	 */
	private function getArticleColumn($columnName, Html5Wiki_Model_ArticleVersion $article = null, array $data = array()) {
		if (isset($article->$columnName) && 
				(!isset($data[$columnName]) || isset($data[$columnName]) && empty($data[$columnName]))) {
			return $article->$columnName;
		} elseif (isset($data[$columnName])) {
			return $data[$columnName];
		}
		return '';
	}
	
	
	/**
	 * Show the editor for an article. 
	 * 
	 * @param array $preparedData
	 * @param array $errors 
	 */
	private function showArticleEditor(array $preparedData, array $errors = array()) {
		$this->setTemplate('edit.php');
		
		if (!empty($preparedData['title'])) {
			$this->setPageTitle($preparedData['title']);
		} else {
			$this->setPageTitle($preparedData['permalink']);
		}
		
		if ($preparedData['tags'] instanceof Zend_Db_Table_Rowset_Abstract) {
			$this->template->assign('tags', $this->getFormattedTags($preparedData['tags']));
			unset($preparedData['tags']);
		}
		
		foreach ($preparedData as $key => $value) {
			$this->template->assign($key, $value);
		}
		
		$this->template->assign('request', $this->router->getRequest());
        
        $this->template->assign('error', $errors);
	}
	
	/**
	 * Save the edited Article and forward to the Article 
	 * or if the validation failed, to the edit page
	 */
	public function saveAction() {
		$params	= $this->matchPostParamsWithColumns();
		$permalink = $this->getPermalink();
		
		if ($this->router->getRequest()->isAjax()) {
			$this->setNoLayout();
		}
		$oldArticleVersion = new Html5Wiki_Model_ArticleVersion();
		if ($params['mediaVersionId'] !== 0 && $params['mediaVersionTimestamp'] !== 0) {
			$oldArticleVersion->loadByIdAndTimestamp($params['mediaVersionId'], $params['mediaVersionTimestamp']);
		}

        $errors = array();
		$user = $this->getUser($params);
		if ($user !== false && $this->validateArticleEditForm($params, $errors)) {
			$articleVersion = $this->saveArticle($permalink, $user, $this->prepareData($oldArticleVersion, $params));
			
			// reload the article because it needs also the MediaVersion informations.
			$article = new Html5Wiki_Model_ArticleVersion();
			$article->loadByIdAndTimestamp($articleVersion->mediaVersionId, $articleVersion->mediaVersionTimestamp);
			
			$this->showArticle($article);
        } else {
			$this->template->assign('errors', $errors);
			$this->showArticleEditor($this->prepareData($oldArticleVersion, $params));
        }
	}
	
	/**
	 * Save an article
	 * 
	 * @param string $permalink
	 * @param Html5Wiki_Model_User $user
	 * @param array $params
	 * @return Html5Wiki_Model_ArticleVersion_Table 
	 */
	private function saveArticle($permalink, $user, array $params) {
		$mediaVersionTable = new Html5Wiki_Model_MediaVersion_Table();
		$data = array(
			'timestamp' => time(),
			'permalink' => $permalink,
			'userId' => $user->id,
			'versionComment' => $params['versionComment']
		);

		if ($params['mediaVersionId'] !== 0) {
			$data['id'] = $params['mediaVersionId'];
		}
		$mediaVersionRow = $mediaVersionTable->createRow($data);
		$primaryKeys = $mediaVersionRow->save();

		$articleVersion = new Html5Wiki_Model_ArticleVersion_Table();
		$article = $articleVersion->createRow(array(
			'mediaVersionId' => $primaryKeys['id'],
			'mediaVersionTimestamp' => $primaryKeys['timestamp'],
			'title' => $params['title'],
			'content' => $params['content']
		));
		$article->save();

		$tags = explode(',', $params['tags']);
		$this->saveTags($tags, $primaryKeys['id'], $primaryKeys['timestamp']);

		return $article;
	}
	
	
	/**
	 * Saves tags
	 * @param array $tags
	 * @param int $mediaVersionId
	 * @param int $mediaVersionTimestamp 
	 */
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
	 * Returns user. When already set as field, use it. Otherwise handle user request according to params.
	 * 
	 * For preventing losing user informations when only doing an ajax request (which does not send cookie-headers).
	 * @param array $params
	 * @return Html5Wiki_Model_User 
	 */
	private function getUser(array $params = array()) {
		if ($this->user === null) {
			$this->user = $this->handleUserRequest($params);
		}
		return $this->user;
	}
	
	/**
	 * 
	 * @param array $params
	 * @return bool|Html5Wiki_Model_User
	 */
	private function handleUserRequest(array $params) {
		$userData = array();
		if (count($params)) {
			$userData	= array(
				'name'      => $params['authorName'],
				'email' 	=> $params['authorEmail']
			);
			if (isset($params['userId']) && $params['userId'] !== 0) {
				$userData['id'] = $params['userId'];
			}
		}
		$user = new Html5Wiki_Model_User(array('data' => $userData));
		if(isset($user->id) && $user->id > 0) {
			return $user;
		} elseif (count($params) && strlen($params['authorName']) && strlen($params['authorEmail'])) {
			$userTable = new Html5Wiki_Model_User_Table();
			$existingUser = $userTable->userExists($userData['name'], $userData['email']);
			if (isset($existingUser->id)) {
				return $existingUser;
			}
			$user = $userTable->createRow(array('email' => $userData['email'], 'name' => $userData['name']));
			$user->save();

			return $user;
		}
		
		return null;
	}
	
	/**
	 * Matches form post params with table columns.
	 * @return array 
	 */
	private function matchPostParamsWithColumns() {
		$request = $this->router->getRequest();
		return array(
			'mediaVersionId'    => intval($request->getPost('hiddenIdArticle', 0)),
			'mediaVersionTimestamp' => intval($request->getPost('hiddenTimestampArticle', 0)),
			'title' => $request->getPost('txtTitle', ''),
			'content' => $request->getPost('contentEditor', ''),
			'tags'    => $request->getPost('tags', ''),
			'versionComment' => $request->getPost('versionComment'),
			'userId' => intval($request->getPost('hiddenAuthorId', 0)),
			'authorName' => $request->getPost('txtAuthor'),
			'authorEmail' => $request->getPost('txtAuthorEmail')
		);
	}
	
	
    /**
     * Validate the form data after saving the article
     *
	 * @param array $params
	 * @param array $errors Reference to an errors array
	 * 
     * @author Alexandre Joly <ajoly@hsr.ch>
	 * 
	 * @return bool
     */
    private function validateArticleEditForm(array $params, array &$errors) {
        $success = true;
        $errorMsg = array();
        $errorFields = array(
            'title' => false,
            'content' => false,
            'authorName' => false,
            'authorEmail' => false,
            'tags' => false,
            'versionComment' => false
        );

        // Test Title
        $validatorChainTitle = new Zend_Validate();
        $validatorChainTitle->addValidator(new Zend_Validate_Alnum(true))
                            ->addValidator(new Zend_Validate_StringLength(self::TITLEFIELD_MIN_LENGTH, self::TITLEFIELD_MAX_LENGTH));
		$success = $this->validatorIsValid($validatorChainTitle, 'title', $params['title'], $errorMsg, $errorFields);

        // Test Content
        $validatorChainContent = new Zend_Validate();
        $validatorChainContent->addValidator(new Zend_Validate_NotEmpty());
		$success = $this->validatorIsValid($validatorChainContent, 'content', $params['content'], $errorMsg, $errorFields);

        // Test Tag
        $validatorChainTags = new Zend_Validate();
        $validatorChainTags->addValidator(new Zend_Validate_NotEmpty());
        $success = $this->validatorIsValid($validatorChainTags, 'tags', $params['tags'], $errorMsg, $errorFields);
		
		if (!empty($params['versionComment'])) {
			$validatorChainVersionComment = new Zend_Validate();
			$validatorChainVersionComment->addValidator(new Zend_Validate_Alpha());
			$success = $this->validatorIsValid($validatorChainVersionComment, 'versionComment', $params['versionComment'], $errorMsg, $errorFields);
		}
		
        $errors['messages'] = $errorMsg;
        $errors['fields'] = $errorFields;
		
		if ($params['authorName'] !== null || $params['authorEmail'] !== null) {
			$success = $this->validateAuthor($params, $errors);
		}

        return $success;
    }
	
	/**
	 * Validator author name & email
	 * 
	 * @param array $params
	 * @param array $errors Reference to an errors array
	 * @return boolean 
	 */
	private function validateAuthor(array $params, array &$errors) {
		$success = true;
		$errorMsg = array();
		$errorFields = array();
		
		$validatorChainName = new Zend_Validate();
		$validatorChainName->addValidator(new Zend_Validate_StringLength(self::USERNAME_MIN_LENGTH, self::USERNAME_MAX_LENGTH))
						   ->addValidator(new Zend_Validate_Alpha());
		$success = $this->validatorIsValid($validatorChainName, 'authorName', $params['authorName'], $errorMsg, $errorFields);
		
		$validatorChainEmail = new Zend_Validate();
		$validatorChainEmail->addValidator(new Zend_Validate_EmailAddress());
		$success = $this->validatorIsValid($validatorChainEmail, 'authorEmail', $params['authorEmail'], $errorMsg, $errorFields);
		
		$errors['messages'] = array_merge($errors['messages'], $errorMsg);
		$errors['fields'] = array_merge($errors['fields'], $errorFields);
		
		return $success;
	}
	
	/**
	 * Check if validator chain is valid & maybe fill in errors in the errorMsg & errorFields params.
	 * 
	 * @param Zend_Validate $validatorChain
	 * @param string $key
	 * @param string $value
	 * @param array $errorMsg
	 * @param array $errorFields
	 * 
	 * @return boolean 
	 */
	private function validatorIsValid($validatorChain, $key, $value, array &$errorMsg, array &$errorFields) {
		$success = true;
		if (!$validatorChain->isValid($value)) {
			$success = false;
			
            foreach ($validatorChain->getMessages() as $message) {
                array_push($errorMsg, $key . ' ' . $message);
                $errorFields[$key] = true;
            }
		}
		return $success;
	}
	
	/**
	 * Handles an edit-request for the given permalink in the url.<br/>
	 * If the permalink was not found, the user gets redirected to the search
	 * page where he can choose to create a fresh article with the permalink
	 * entered.
	 */
	public function editAction() {
		$parameters = $this->router->getRequest()->getGetParameters();
		$permalink = $this->getPermalink();

		if ($this->router->getRequest()->isAjax()) {
			$this->setNoLayout();
			$article = new Html5Wiki_Model_ArticleVersion();
			$article->loadLatestById($parameters['idArticle']);
		} else {
			$article = new Html5Wiki_Model_ArticleVersion();
			$article->loadLatestByPermalink($permalink);
		}
	
		if($article == null) {
			$this->redirectToArticleNotFoundSearch($permalink);
		} else {
			$this->template->assign('author', $this->getUser());
			$this->showArticleEditor($this->prepareData($article, array('tags' => $article->getTags())));
		}
	}
	
	
	/**
	 * Show history overview of an article. 
	 * 
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
		
		$this->setPageTitle($latestVersion->title);
		$this->template->assign('article', $latestVersion);
		$this->template->assign('versions', $groupedVersions);
	}
	
	/**
	 * Show a diff between to versions, previously selected by the history page.
	 * 
	 * @uses PhpDiff_Diff
	 */
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
		$this->setPageTitle($leftVersion->getCommonName());
	}
	
	/**
	 * Formats a tagset by imploding it with commas.
	 * 
	 * @param Zend_Db_Table_Rowset_Abstract $tagset
	 * @return string 
	 */
	private function getFormattedTags(Zend_Db_Table_Rowset_Abstract $tagset) {

		$tags = array();
		foreach ($tagset as $tag) {
			$tags[] = (string)$tag;
		}
		
		return implode(", ", $tags);
	}
 }

?>
