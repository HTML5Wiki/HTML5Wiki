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
		
		$html = '
   <ol class="capsulebar">
		<li class="item first'. (($activePage !== 'edit' && $activePage !== 'history') ? ' active' : '') .' read">
			<a href="'. $urlHelper->urlHelper('wiki/' . $permalink) .'" class="capsule" id="capsulebar-read">
				<span class="caption">' . $this->template->getTranslate()->_('read'). '</span>
			</a>
		</li>
		<li class="item edit'. ($activePage === 'edit' ? ' active' : '') .'">
			<a href="'. $urlHelper->urlHelper('wiki/edit/' . $permalink) .'" class="capsule" id="capsulebar-edit">
				<span class="caption">' . $this->template->getTranslate()->_('edit') . '</span>
			</a>
		</li>
		<li class="item last'. ($activePage === 'history' ? ' active' : '') .' history">
			<a href="'. $urlHelper->urlHelper('wiki/history/' . $permalink) .'" class="capsule" id="capsulebar-history">
				<span class="caption">' . $this->template->getTranslate()->_('history') . '</span>
			</a>
		</li>
	</ol>';

		
		return $html;
	}
	
}

?>
