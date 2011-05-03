<?php
/**
 * Index Controller for the overall history page
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @package Application
 */
class Application_IndexController extends Html5Wiki_Controller_Abstract {
    public function historyAction() {
		$articleTable = new Html5Wiki_Model_Article_Table();
		$latestArticles = $articleTable->fetchLatestArticles();
		
		$this->template->assign('latestArticles', $latestArticles);
		$this->layoutTemplate->assign('title', $this->layoutTemplate->getTranslate()->_('recentChanges'));
	}
}
?>
