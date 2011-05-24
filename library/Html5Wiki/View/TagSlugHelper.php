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
