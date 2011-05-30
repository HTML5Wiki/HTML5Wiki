<?php
/**
 * This file is part of the HTML5Wiki Project.
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright Html5Wiki 2011
 * @package Html5Wiki
 * @subpackage Library
 */

/**
 * Capsulebar Helper
 */
class Html5Wiki_View_CapsulebarHelper extends Html5Wiki_View_Helper {

	public function capsulebarHelper($args) {
		$router = Html5Wiki_Controller_Front::getInstance()->getRouter();
		
		$urlHelper = new Html5Wiki_View_UrlHelper($this->template);
		$activePage = $router->getAction();
		
		$permalink = $args[0];
		
		$response = new Html5Wiki_Routing_Response();
		
		$template = new Html5Wiki_Template_Php($response);
		$template->setTemplateFile('helpers/capsulebar.php');
		$template->assign('activePage', $activePage);
		$template->assign('permalink', $permalink);
		$template->setTranslate($this->template->getTranslate());
		
		$template->render();
		return $response->getData();
	}
	
}

?>
