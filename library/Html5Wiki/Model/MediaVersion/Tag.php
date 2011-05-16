<?php
/**
 * This file is part of the HTML5Wiki Project.
 *
 * @author		Michael Weibel <mweibel@hsr.ch>
 * @copyright	(c) HTML5Wiki Team 2011
 * @package		Html5Wiki
 * @subpackage	Library
 */

class Html5Wiki_Model_MediaVersion_Tag extends Html5Wiki_Model_MediaVersion {
	
	protected $_tableClass = 'Html5Wiki_Model_MediaVersion_Mediatag_Table';
	
	public function __toString() {
		return $this->tagTag;
	}
}

?>