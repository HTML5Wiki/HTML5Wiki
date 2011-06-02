<?php
/**
 * This file is part of the HTML5Wiki Project.
 * 
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.github.com/HTML5Wiki/HTML5Wiki/blob/master/LICENSE
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to mweibel@hsr.ch so we can send you a copy immediately.
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @author Manuel Alabor <malabor@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @category Html5Wiki
 * @package Library
 * @subpackage View
 */

/**
 * Tag slug rendering Helper
 */
class Html5Wiki_View_TagSlugHelper extends Html5Wiki_View_Helper {

	public function tagSlugHelper($args) {
		$tags = $args[0];
		
		$tagSlug = '';
		$tagTemplate = '<a href="'
					 . $this->template->request->getBasePath()
					 . '/index/search?term=%s&amp;mediatype=tag" title="'
					 . $this->template->translate->_('searchForOtherObjectsWithTag')
					 . '" class="tag">%s</a>';
		
		for($i = 0, $l = sizeof($tags); $i < $l; $i++) {
			$tag = $tags[$i];
			$tagSlug .= sprintf($tagTemplate, $tag, $tag, $tag);
			if($i < $l-1) {
				$tagSlug .= ', ';
			}
		}
		
		return $tagSlug;
	}
	
}
?>
