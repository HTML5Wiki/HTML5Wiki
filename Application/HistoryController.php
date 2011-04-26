<?php
/**
 * Created by JetBrains PhpStorm.
 * User: nicolas
 * Date: 26.04.11
 * Time: 15:18
 * To change this template use File | Settings | File Templates.
 */
 
class Application_HistoryController extends Html5Wiki_Controller_Abstract {

	/**
	 * @override
	 * @param Html5Wiki_Routing_Interface_Router $router
	 */
	public function dispatch(Html5Wiki_Routing_Interface_Router $router) {
		try {
			parent::dispatch($router);
		} catch (Html5Wiki_Exception_404 $e) {
			'default history page';
		}
	}

	public function indexAction() {
		
	}

	public function articlehistoryAction() {
		$parameters = $this->router->getRequest()->getPostParameters();

		if( isset($parameters['ajax']) ) {
			$this->setNoLayout();
			// @todo change to all version of this idArticle (no timestamp)
			$wikiPages   = Html5Wiki_Model_ArticleManager::getArticlesById($parameters['idArticle']);
		} else {
			$permalink  = $this->getPermalink();
			$wikiPages   = Html5Wiki_Model_ArticleManager::getArticleByPermaLink($permalink);
		}

		if( $wikiPages == null ) throw new Html5Wiki_Exception_404();
		
		$this->setTemplate('index.php');
		$this->template->assign('wikiPages', $wikiPages);
	}
}
