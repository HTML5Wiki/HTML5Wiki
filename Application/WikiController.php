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
		$this->template->assign('foo', 'bar');
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


			$uri = $router->getRequest()->getUri();
			$permalink = explode('/', $uri, 3);
			$permalink = $permalink[2];

			$article = new Html5Wiki_Model_Media_Table();
			$wikiPage = $article->fetchArticleVersionByPermalink($permalink);

			if ($wikiPage === null) {
				throw new Html5Wiki_Exception_404('Wikipage "' . $permalink . '" not found.');
			}

			$this->template->assign('title', $wikiPage->title);
			$this->template->assign('content', $wikiPage->content);
		}
	}
}

?>
