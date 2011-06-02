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
 * @subpackage Model
 */

/**
 * Tag row model
 */
class Html5Wiki_Model_Tag extends Html5Wiki_Model_Abstract {
	
	protected $_tableClass = 'Html5Wiki_Model_MediaVersion_Mediatag_Tag_Table';
	
	public function loadByTag($tag) {
		$select = $this->select();
		$select->where('tag = ?', $tag);
		
		$tag = $this->_getTable()->fetchRow($select);
		if (isset($tag->tag)) {
			$data = $tag->toArray();

			$this->_data = $data;
			$this->_cleanData = $this->_data;
			$this->_modifiedFields = array();
		}
	}
}

?>