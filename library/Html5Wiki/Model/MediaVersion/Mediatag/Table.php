<?php
/**
 * This file is part of the HTML5Wiki Project.
 *
 * @author		Nicolas Karrer <nkarrer@hsr.ch>
 * @copyright	(c) HTML5Wiki Team 2011
 * @package		Html5Wiki
 * @subpackage	Library
 */


class Html5Wiki_Model_Media_Mediatag_Table extends Zend_Db_Table_Abstract {
		
	/**
	 * 
	 * @var string
	 */
	protected $_name		= 'MediaVersionTag';
	
	/**
	 * 
	 * @var array
	 */
	protected $_primary		= array('tagTag', 'mediaVersionId');
	
	/**
	 * 
	 * @var boolean
	 */
	protected $_sequence	= false;
	
	
}
?>