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
 * @package Library
 * @subpackage View
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
	 * @param string $key (single action name, or more than one, comma-separated)
	 * @param string $text
	 * @param string $cssClass
	 * @param string $url (optional)
	 * @param bool   $default (optional)
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
	 * @param bool $use
	 */
	public function useDefaultItems($use) {
		$this->useDefaultItems = $use;
	}

	/**
	 * Returns the capsulebar template.
	 *
	 * @param array $args
	 * @return string rendered template
	 */
	public function render($args) {
		$router = Html5Wiki_Controller_Front::getInstance()->getRouter();
		
		$urlHelper = new Html5Wiki_View_UrlHelper($this->template);
		$activePage = $router->getAction();
		$permalink = $args[0];
		$response = new Html5Wiki_Routing_Response();
		
		$items = self::$items;
		if($this->useDetaultItems === false) {
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
