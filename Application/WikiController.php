<?php
/**
 * This file is part of the HTML5Wiki Project.
 * 
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * http://www.github.com/HTML5Wiki/HTML5Wiki/blob/master/LICENSE
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to mweibel@hsr.ch so we can send you a copy immediately.
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @category Html5Wiki 
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
	 * Expire Time for Cookies
	 * @var int
	 */
	const USER_COOKIE_EXPIRE = 31536000; // 1 Year

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
		$this->addDefaultWikiCapsuleBarItems();
		$permalink = $this->checkAndGetPermalink();

		if ($this->router->getRequest()->isAjax()) {
			$this->setNoLayout();

			$article = new Html5Wiki_Model_ArticleVersion();
			$article->loadLatestById($this->router->getRequest()->getGet('idArticle'));
		} else {
			$article = new Html5Wiki_Model_ArticleVersion();
			$article->loadLatestByPermalink($permalink);
		}

		if (isset($article->id)) {
			if($this->router->getRequest()->isAjax() === false) {
				$this->layoutTemplate->assign('permalink', $permalink); // used in menubar
			}
			$this->showArticle($article);
		} else {
			$this->redirectToArticleNotFoundSearch($permalink);
		}
	}

	/**
	 * Set ETag and LastModified caching headers
	 *
	 * @param Html5Wiki_Model_ArticleVersion $article
	 */
	private function setCachingHeader(Html5Wiki_Model_ArticleVersion $article) {
		$this->setETag(md5($article->mediaVersionId . $article->mediaVersionTimestamp));
		$this->setLastModified($article->mediaVersionTimestamp);
	}

	/**
	 * Shows an article model in the proper template.
	 *
	 * @param Html5Wiki_Model_ArticleVersion $article
	 */
	private function showArticle(Html5Wiki_Model_ArticleVersion $article) {
		$this->setTemplate('read.php');

		$this->setCachingHeader($article);
		
		$this->setPageTitle($article->getCommonName());

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
		$searchUrl = $this->router->buildURL(array('index','search?term='. $permalink. '&newarticle=1'));
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
		if ($this->router->getRequest()->isAjax()) {
			$this->setNoLayout();
		}
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
			'mediaVersionId' => $this->getArticleColumn('mediaVersionId', $article, $data),
			'mediaVersionTimestamp' => $this->getArticleColumn('mediaVersionTimestamp', $article, $data),
			'title' => $this->getArticleColumn('title', $article, $data),
			'content' => $this->getArticleColumn('content', $article, $data),
			'userId' => $this->getArticleColumn('userId', $article, $data),
			'tags' => $this->getArticleColumn('tags', $article, $data),
			'versionComment' => $this->getArticleColumn('versionComment', null, $data),
			'permalink' => $this->getArticleColumn('permalink', $article, $data)
		);
	}

	/**
	 * Gets column data from either the article-param or the data param. The data param takes precedence.
	 * @param string $columnName
	 * @param Html5Wiki_Model_ArticleVersion $article
	 * @param array $data
	 * @return string
	 */
	private function getArticleColumn($columnName, Html5Wiki_Model_ArticleVersion $article = null,
			array $data = array()) {
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
	private function showArticleEditor(array $preparedData) {
		$this->setTemplate('edit.php');
		$this->setNoCache();

		if (!empty($preparedData['title'])) {
			$this->setPageTitle($preparedData['title']);
		} else if(empty($preparedData['permalink'])) {
			$this->setPageTitle($this->template->translate->_('newArticle'));
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
		$this->template->assign('author', $this->getUser());
	}

	/**
	 * Save the edited Article and forward to the Article
	 * or if the validation failed, to the edit page
	 */
	public function saveAction() {
		$this->addDefaultWikiCapsuleBarItems();
		$params = $this->matchPostParamsWithColumns();
		$permalink = $this->getPermalink();

		if ($this->router->getRequest()->isAjax()) {
			$this->setNoLayout();
		}
		$oldArticleVersion = new Html5Wiki_Model_ArticleVersion();
		if ($params['mediaVersionId'] !== 0 && $params['mediaVersionTimestamp'] !== 0) {
			$oldArticleVersion->loadByIdAndTimestamp($params['mediaVersionId'],
					$params['mediaVersionTimestamp']);
		}

		$errors = $this->createEmptyErrorArray();
		$user = $this->getUser($params);
		
		if ($user !== false && $this->validateArticleEditForm($oldArticleVersion, $params, $errors)) {
			if ($this->hasIntermediateVersion($oldArticleVersion, $permalink) && !$params['overwrite']) {
				/* Tell the user, that there is an intermediate version: */
				$intermediateArticle = new Html5Wiki_Model_ArticleVersion();
				$intermediateArticle->loadLatestByPermalink($permalink);

				$oldArticleVersion = $intermediateArticle;

				$userMediaVersion = $this->createMediaVersion($permalink, $user, $this->prepareData($oldArticleVersion, $params));
				$userArticleVersion = $this->createArticleVersion($userMediaVersion, $this->prepareData($oldArticleVersion, $params));

				$diff = $this->createLeftRightDiffContent($userArticleVersion, $intermediateArticle);
				$this->template->assign('diff', $diff);
				$this->template->assign('leftVersionTitle', $this->template->translate->_('myVersion'));
				$this->template->assign('rightVersionTitle', $this->template->translate->_('newVersion'));
				$this->template->assign('otherAuthor', $intermediateArticle->getUser()->name);
			} else {
				/* Everythings fine. Create version: */
				// If the user created an entirly fresh article without entering
				// a permalink, create a new permalink out of the title.
				if(strlen($permalink) === 0) {
					$permalink = $this->createPermalinkFromString($params['title']);
				}
				
				// Save:
				$articleVersion = $this->saveArticle($permalink, $user, $this->prepareData($oldArticleVersion, $params));
				
				// Redirect:
				$url = $this->router->buildURL(array('wiki',$permalink));
				if($this->router->getRequest()->isAjax() === true) {
					$this->setNoLayout();
					echo $url;
					$this->doRenderAndExit();
				} else {
					$this->redirect($url);
				}
				
				return;
			}
		}
		
		$this->setHttpResponseStatus(400);
		$this->template->assign('permalink', $permalink);
        $this->template->assign('errors', $errors);
        $this->showArticleEditor($this->prepareData($oldArticleVersion, $params));
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
		$mediaVersionRow = $this->createMediaVersion($permalink, $user, $params);
		$primaryKeys = $mediaVersionRow->save();

		$article = $this->createArticleVersion($primaryKeys, $params);
		$article->save();

		$tags = explode(',', $params['tags']);
		$this->saveTags($tags, $primaryKeys['id'], $primaryKeys['timestamp']);

		return $article;
	}

	/**
	 * Create a new ArticleVersion row
	 *
	 * @param  $mediaVersion
	 * @param array $params
	 * @return Zend_Db_Table_Row_Abstract
	 */
	private function createArticleVersion($mediaVersion = null, array $params = array()) {
		$data = array();
		if ($mediaVersion !== null) {
			$data['mediaVersionId'] = $mediaVersion['id'];
			$data['mediaVersionTimestamp'] = $mediaVersion['timestamp'];
		}
		$data['title'] = $params['title'];
		$data['content'] = $params['content'];

		$articleVersion = new Html5Wiki_Model_ArticleVersion_Table();
		$article = $articleVersion->createRow($data);
		return $article;
	}

	/**
	 * Create a new MediaVersion row
	 *
	 * @param  $permalink
	 * @param  $user
	 * @param array $params
	 * @return Zend_Db_Table_Row_Abstract
	 */
	private function createMediaVersion($permalink, $user, array $params) {
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
		return $mediaVersionRow;
	}

	/**
	 * Saves tags
	 * @param array $tags
	 * @param int $mediaVersionId
	 * @param int $mediaVersionTimestamp
	 */
	private function saveTags(array $tags, $mediaVersionId, $mediaVersionTimestamp) {
		foreach ($tags as $tag) {
			$tag = trim($tag);
			
			if(strlen($tag) > 0) {
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
			$user = $this->handleUserRequest($params);
			if ($user) {
				$this->setUser($user);
			}
		}
		return $this->user;
	}
	
	/**
	 * Set user and save user cookie
	 * 
	 * @param Html5Wiki_Model_User $user 
	 * @return Html5Wiki_Model_User
	 */
	private function setUser(Html5Wiki_Model_User $user) {
		$this->user = $user;
		$this->saveUserCookie($user);
		return $user;
	}
	
	/**
	 * Push user cookie to response.
	 * 
	 * @param Html5Wiki_Model_User $user 
	 */
	private function saveUserCookie(Html5Wiki_Model_User $user) {
		$this->response->pushCookie('currentUserId', $user->id, time() + self::USER_COOKIE_EXPIRE, '/', null, false, true);
	}

	/**
	 * Handle user request by checking params and getting the correct user if possible.
	 *
	 * @param array $params
	 * @return mixed Html5Wiki_Model_User or null
	 */
	private function handleUserRequest(array $params) {
		$user = new Html5Wiki_Model_User();
		// load user by id
		if (isset($params['userId']) && !isset($params['authorName']) && !isset($params['authorEmail'])) {
			$user->loadById($params['userId']);
			if (isset($user->id)) {
				return $user;
			}
		}
		// check if correct userId has been loaded
		if (isset($params['userId']) && isset($params['authorName']) && isset($params['authorEmail'])) {
			$user->loadByIdNameAndEmail($params['userId'], $params['authorName'], $params['authorEmail']);
			if (isset($user->id)) {
				return $user;
			}
		}
		if (isset($params['authorName']) && isset($params['authorEmail']) && strlen($params['authorName']) && strlen($params['authorEmail'])) {
			$userTable = new Html5Wiki_Model_User_Table();
			$existingUser = $userTable->userExists($params['authorName'], $params['authorEmail']);
			if (isset($existingUser->id)) {
				return $existingUser;
			}
			$user = $userTable->createRow(array('email' => $params['authorEmail'], 'name' => $params['authorName']));
			$user->save();

			return $user;
		}

		// as a last option, load from cookie
		$user->loadFromCookie();
		if (isset($user->id)) {
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
			'mediaVersionId' => intval($request->getPost('hiddenIdArticle', 0)),
			'mediaVersionTimestamp' => intval($request->getPost('hiddenTimestampArticle', 0)),
			'title' => $request->getPost('txtTitle', null),
			'content' => $request->getPost('contentEditor', ''),
			'tags' => $request->getPost('tags', ''),
			'versionComment' => $request->getPost('versionComment'),
			'userId' => intval($request->getPost('hiddenAuthorId', 0)),
			'authorName' => $request->getPost('txtAuthor'),
			'authorEmail' => $request->getPost('txtAuthorEmail'),
			'overwrite' => $request->getPost('hiddenOverwrite', 0)
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
	private function validateArticleEditForm(Html5Wiki_Model_ArticleVersion $oldArticleVersion,
			array $params, array &$errors) {
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
		if ($params['title'] !== null) {
			$validatorChainTitle = new Zend_Validate();
			$validatorChainTitle->addValidator(new Zend_Validate_Regex('/[a-z0-9\?\.-_\/\,]/i'))
					->addValidator(new Zend_Validate_StringLength(self::TITLEFIELD_MIN_LENGTH, self::TITLEFIELD_MAX_LENGTH));
			$success = $this->validatorIsValid($success, $validatorChainTitle, 'title', $params['title'],
							$errorMsg, $errorFields);
		}

		// Test Content
		$validatorChainContent = new Zend_Validate();
		$validatorChainContent->addValidator(new Zend_Validate_NotEmpty());
		$success = $this->validatorIsValid($success, $validatorChainContent, 'content',
						$params['content'], $errorMsg, $errorFields);

		if (!empty($params['versionComment'])) {
			$validatorChainVersionComment = new Zend_Validate();
			$validatorChainVersionComment->addValidator(new Zend_Validate_Alnum(true));
			$success = $this->validatorIsValid($success, $validatorChainVersionComment, 'versionComment',
							$params['versionComment'], $errorMsg, $errorFields);
		}

		$errors['messages'] = $errorMsg;
		$errors['fields'] = $errorFields;

		if ($params['authorName'] !== null || $params['authorEmail'] !== null) {
			$success = $this->validateAuthor($success, $params, $errors);
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
	private function validateAuthor($success, array $params, array &$errors) {
		$errorMsg = array();
		$errorFields = array();

		$validatorChainName = new Zend_Validate();
		$validatorChainName->addValidator(new Zend_Validate_StringLength(self::USERNAME_MIN_LENGTH, self::USERNAME_MAX_LENGTH))
				->addValidator(new Zend_Validate_Alpha(true));
		$success = $this->validatorIsValid($success, $validatorChainName, 'authorName',
						$params['authorName'], $errorMsg, $errorFields);

		$validatorChainEmail = new Zend_Validate();
		$validatorChainEmail->addValidator(new Zend_Validate_EmailAddress());
		$success = $this->validatorIsValid($success, $validatorChainEmail, 'authorEmail',
						$params['authorEmail'], $errorMsg, $errorFields);

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
	private function validatorIsValid($success, $validatorChain, $key, $value, array &$errorMsg,
			array &$errorFields) {
		if (!$validatorChain->isValid($value)) {
			$success = false;

			foreach ($validatorChain->getMessages() as $message) {
				array_push($errorMsg, $this->template->getTranslate()->_($key) . ' ' . $message);
				$errorFields[$key] = true;
			}
		}
		return $success;
	}

	/**
	 * Prepares an array for adding error messages which are generated from the
	 * validatior-functions in this class.
	 *
	 * @return array
	 */
	private function createEmptyErrorArray() {
		return array('messages'=>array(),'fields'=>array());
	}

    /**
     * Check if the article was save from an other user while editing
     *
     * @param  Html5Wiki_Model_ArticleVersion $oldArticleVersion
     * @return boolean
     */
    private function hasIntermediateVersion(Html5Wiki_Model_ArticleVersion $oldArticleVersion, $permalink = null) {
		if($permalink === null) $permalink = $this->checkAndGetPermalink();

		$latestArticle = new Html5Wiki_Model_ArticleVersion();
		$latestArticle->loadLatestByPermalink($permalink);

		if (!isset($latestArticle->id) && !isset($oldArticleVersion->id)) {
			return false;
		}

        return 	(!isset($oldArticleVersion->id) && isset($latestArticle->id)) ||
				!($latestArticle->id === $oldArticleVersion->id &&
                 $latestArticle->timestamp === $oldArticleVersion->timestamp);
    }

	/**
	 * Handles an edit-request for the given permalink in the url.<br/>
	 * If the permalink was not found, the user gets redirected to the search
	 * page where he can choose to create a fresh article with the permalink
	 * entered.
	 */
	public function editAction() {
		$this->addDefaultWikiCapsuleBarItems();
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
		
		if (!isset($article->id)) {
			$this->redirectToArticleNotFoundSearch($permalink);
		} else {
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
		$this->addDefaultWikiCapsuleBarItems();
		$parameters = $this->router->getRequest()->getGetParameters();
		$mediaManager = new Html5Wiki_Model_MediaVersionManager();

		if ($this->router->getRequest()->isAjax()) {
			$this->setNoLayout();
			$id = $parameters['idArticle'];
			$versions = $mediaManager->getMediaVersionsById($id);
		} else {
			$permalink = $this->getPermalink();
			$versions = $mediaManager->getMediaVersionsByPermalink($permalink);
		}

		if (count($versions) == 0) {
			throw new Html5Wiki_Exception_404();
		}

		$latestVersion = new Html5Wiki_Model_ArticleVersion();
		$latestVersion->loadLatestById($versions->current()->id);
		$groupedVersions = $mediaManager->groupMediaVersionByTimespan($versions);

		$this->setCachingHeader($latestVersion);

		$this->setPageTitle($latestVersion->getCommonName());
		$this->template->assign('article', $latestVersion);
		$this->template->assign('versions', $groupedVersions);
	}

	/**
	 * Show a diff between to versions, previously selected by the history page.
	 *
	 * @uses PhpDiff_Diff
	 */
	public function diffAction() {
		$this->addDefaultWikiCapsuleBarItems();
		$request = $this->router->getRequest();
		$left = $request->getGet('left');
		$right = $request->getGet('right');

		$permalink = $this->checkAndGetPermalink();

		if (!$left || !$right) {
			throw new Html5Wiki_Exception("Left or right must be supplied. TODO: Redirect to history.");
		}

		$mediaManager = new Html5Wiki_Model_MediaVersionManager();
		$versions = $mediaManager->getMediaVersionsByPermalinkAndTimestamps($permalink,
						array($left, $right));

		$leftVersion = null;
		$rightVersion = null;

		foreach ($versions as $version) {
			$articleVersion = new Html5Wiki_Model_ArticleVersion();
			$articleVersion->loadByIdAndTimestamp($version->id, $version->timestamp);
			if ($articleVersion->mediaVersionTimestamp === intval($left)) {
				$leftVersion = $articleVersion;
			} else {
				$rightVersion = $articleVersion;
			}
		}

		$latestTimestamp = max($leftVersion->mediaVersionTimestamp, $rightVersion->mediaVersionTimestamp);
		if($latestTimestamp == $leftVersion->mediaVersionTimestamp) {
			$latestArticle = $leftVersion;
		} else {
			$latestArticle = $rightVersion;
		}
		$this->setCachingHeader($latestArticle);

		$diff = $this->createLeftRightDiffContent($leftVersion, $rightVersion);

		$this->template->assign('diff', $diff);
		$this->template->assign('title', $latestArticle->getCommonName());
		$this->template->assign('leftTimestamp', $left);
		$this->template->assign('rightTimestamp', $right);

		$this->template->assign('permalink', $permalink);

		$this->setPageTitle($leftVersion->getCommonName());
	}

	/**
	 * Create the content for PhpDiff and return the Diff
	 *
	 * @param Html5Wiki_Model_ArticleVersion $leftVersion
	 * @param Html5Wiki_Model_ArticleVersion $rightVersion
	 *
	 * @return PhpDiff_Diff
	 */
	private function createLeftRightDiffContent(Html5Wiki_Model_ArticleVersion $leftVersion, Html5Wiki_Model_ArticleVersion $rightVersion) {
		$translatedTitle = $this->template->getTranslate()->_('title');
		$translatedTags   = $this->template->getTranslate()->_('tags');

		$leftContent = $translatedTitle . ': ' . $leftVersion->getCommonName();
		if (isset($leftVersion->id) && isset($leftVersion->timestamp)
		&& isset($rightVersion->id) && isset($rightVersion->timestamp)) {
			$leftContent .= "\n\n" . $translatedTags . ': '
							. $this->getFormattedTags($leftVersion->getTags());
		}
		$leftContent .= "\n\n" . $leftVersion->content;

		$rightContent = $translatedTitle . ': ' . $rightVersion->getCommonName();
		if (isset($leftVersion->id) && isset($leftVersion->timestamp)
		&& isset($rightVersion->id) && isset($rightVersion->timestamp)) {
			$rightContent .= "\n\n" . $translatedTags . ': '
							. $this->getFormattedTags($rightVersion->getTags());
		}
		$rightContent .= "\n\n" . $rightVersion->content;

		return new PhpDiff_Diff(explode("\n", $leftContent), explode("\n", $rightContent));
	}

	public function rollbackAction() {
		$this->addDefaultWikiCapsuleBarItems();
		$permalink = $this->checkAndGetPermalink();
		$request = $this->router->getRequest();

		$toTimestamp = $request->getGet('to');

		if (!$toTimestamp) {
			throw new Html5Wiki_Exception("Timestamp must be supplied. TODO: Redirect to history.");
		}

		$mediaVersion = new Html5Wiki_Model_MediaVersion();
		$mediaVersion->loadByPermalinkAndTimestamp($permalink, $toTimestamp);
		$articleVersion = new Html5Wiki_Model_ArticleVersion();
		$articleVersion->loadByIdAndTimestamp($mediaVersion->id, $toTimestamp);

		if ($request->getPost('rollback')) {
			$userData = array(
				'authorName' => $request->getPost('txtAuthor'),
				'authorEmail' => $request->getPost('txtAuthorEmail')
			);
			$versionComment = $request->getPost('txtVersionComment');
			
			$errors = $this->createEmptyErrorArray();
			if($this->validateAuthor(true, $userData, $errors)) {
				if($versionComment === null || strlen($versionComment) === 0) {
					$versionComment =
						$mediaVersion->versionComment
						. ' ('
						. sprintf($this->template->translate->_('restoredFrom'), date($this->template->translate->_('timestampFormat')))
						. ')';
				}

				$newMediaVersionTable = new Html5Wiki_Model_MediaVersion_Table();
				$newMediaVersion = $newMediaVersionTable->createRow($mediaVersion->toArray());
				$newArticleVersionTable = new Html5Wiki_Model_ArticleVersion_Table();
				$newArticleVersion = $newArticleVersionTable->createRow($articleVersion->toArray());

				$newMediaVersion->timestamp = time();
				$newMediaVersion->userId = $this->getUser($userData)->id;
				$newMediaVersion->versionComment = $versionComment;
				$newArticleVersion->mediaVersionTimestamp = $newMediaVersion->timestamp;

				$newMediaVersion->save();
				$newArticleVersion->save();

				$this->redirect($this->router->buildURL(array('wiki',$permalink)));
			}
			
			$this->template->assign('errors', $errors);
			$this->showRollbackForm($permalink, $toTimestamp, $this->getUser(), $articleVersion->getCommonName());			
		} else {
			$this->showRollbackForm($permalink, $toTimestamp, $this->getUser(), $articleVersion->getCommonName());
		}
	}
	
	/**
	 * Prepares the template to display the rollback form.
	 *
	 * @param $permalink
	 * @param $toTimestamp
	 * @param $author
	 * @param $title
	 */
	private function showRollbackForm($permalink, $toTimestamp, $author, $title) {
		$this->template->assign('permalink', $permalink);
		$this->template->assign('toTimestamp', $toTimestamp);
		$this->template->assign('author', $author);
		$this->template->assign('title', $title);
		$this->setPageTitle($title);
	}


	public function previewAction() {
		$content = $this->router->getRequest()->getPost('data');

		$this->setPageTitle($this->template->getTranslate()->_('preview'));

		$this->template->assign('content', $content);
		$this->template->assign('markDownParser', new Markdown_Parser());
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
			$tags[] = (string) $tag;
		}

		return implode(",", $tags);
	}
	
	/**
	 * Asks the user if he really wants to delete a MediaVersion.<br/>
	 * If yes, all entries on the MediaVersion table get the state "TRASH".
	 */
	public function deleteAction() {
		$this->addDefaultWikiCapsuleBarItems();
		$permalink = $this->checkAndGetPermalink();
		$request = $this->router->getRequest();
		
		$mediaVersion = new Html5Wiki_Model_MediaVersion();
		$mediaVersion->loadLatestByPermalink($permalink);
		$articleVersion = new Html5Wiki_Model_ArticleVersion();
		$articleVersion->loadByIdAndTimestamp($mediaVersion->id, $mediaVersion->timestamp);
		
		$title = $articleVersion->getCommonName();
		
		if($request->getPost('delete')) {
			$table = new Html5Wiki_Model_MediaVersion_Table();
			$table->updateState('TRASH', $mediaVersion->id);
			
			$this->redirect($this->router->buildURL(array('wiki',$permalink)));
		} else {
			$this->template->assign('permalink', $permalink);
			$this->template->assign('title', $title);
			$this->setPageTitle($title);
		}
		
	}
	
	
	/**
	 * Adds the wikis default items (read, edit & history) to the capsulebar
	 * helper.
	 *
	 * @see Html5Wiki_View_CapsulebarHelper
	 */
	private function addDefaultWikiCapsuleBarItems() {
		$permalink = $this->checkAndGetPermalink();
		
		$this->template->capsulebarHelper()->addItem(
			'read'
			,$this->template->translate->_('read')
			,'read'
			,$this->router->buildUrl(array('wiki', $permalink))
			,true
		);
		$this->template->capsulebarHelper()->addItem(
			'edit,save'  // bind edit and save action to this item
			,$this->template->translate->_('edit')
			,'edit'
			,$this->router->buildUrl(array('wiki', 'edit', $permalink))
			,true
		);
		$this->template->capsulebarHelper()->addItem(
			'history'
			,$this->template->translate->_('history')
			,'history'
			,$this->router->buildUrl(array('wiki', 'history', $permalink))
			,true
		);
	}
	
	/**
	 * Creates a permalink out of a string.<br/>
	 * Any special characters like blanks, points, commas etc. get replaced by
	 * a dash ("-"). Further german umlauts get replaced by their two-letter
	 * correspondings.
	 *
	 * @param $string
	 * @return formal valid permalink
	 */
	private function createPermalinkFromString($string) {
		$chartable = array(
			'raw'		=> array('ä'     ,'Ä'     ,'ö'     ,'Ö'     ,'ü'     ,'Ü'     ,'ß'     )
			,'in'		=> array(chr(228),chr(196),chr(246),chr(214),chr(252),chr(220),chr(223))
			,'perma'	=> array('ae'    ,'Ae'    ,'oe'    ,'oe'    ,'ue'    ,'Ue'    ,'ss'    )
		);
		$toReplaceWithDashes = array('.', ',' , '_', ' ');
		$toReplaceWithNothing = array('(', ')');
		
		$result = str_replace($chartable['raw'], $chartable['perma'], $string);
		$result = str_replace($chartable['in'], $chartable['perma'], $result);
		$result = str_replace($toReplaceWithDashes, '-', $result);
		$result = str_replace($toReplaceWithNothing, '', $result);
		$result = strtolower($result);
		
		return $result;
	}

}

?>
