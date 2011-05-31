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
	private static $items = array();
	private $useDetaultItems = true;
	
	/**
	 * Adds an item to the capsulebar.
	 *
	 * @param key (single action name, or more than one, comma-separated)
	 * @param text
	 * @param cssClass
	 * @param url (optional)
	 */
	public function addItem($key, $text, $cssClass, $url='#', $default=false) {
		self::$items[$key] = array(
			'text' => $text
			,'class' => $cssClass
			,'url' => $url
			,'default' => $default
		);
	}
	
	/**
	 * Should the default capsulebar items be displayed?
	 * 
	 * @param $use
	 */
	public function useDefaultItems($use) {
		$this->useDefaultItems = $use;
	}

	/**
	 * Returns the capsulebar template.
	 *
	 * @param args
	 * @return rendered template
	 */
	public function render($args) {
		$router = Html5Wiki_Controller_Front::getInstance()->getRouter();
		
		$urlHelper = new Html5Wiki_View_UrlHelper($this->template);
		$activePage = $router->getAction();
		$permalink = $args[0];
		$response = new Html5Wiki_Routing_Response();
		
		$items = self::$items;
		if($this->useDefaultItems === false) {
			foreach(self::$items as $key => $item) {
				if($item['default'] === false) $items[$key] = $item;
			}
		}
		
		$template = new Html5Wiki_Template_Php($response);
		$template->setTemplateFile('helpers/capsulebar.php');
		$template->assign('activePage', $activePage);
		$template->assign('permalink', $permalink);
		$template->assign('items', $items);
		$template->setTranslate($this->template->getTranslate());
		
		$template->render();
		
		return $response->getData();
	}
	
}

?>
