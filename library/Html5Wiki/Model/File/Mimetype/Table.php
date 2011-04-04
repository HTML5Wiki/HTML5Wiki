<?php
/**
 * This file is part of the HTML5Wiki Project.
 *
 * @author		Nicolas Karrer <nkarrer@hsr.ch>
 * @copyright	(c) HTML5Wiki Team 2011
 * @package		Html5Wiki
 * @subpackage	Library
 */


class Html5Wiki_Model_File_Mimetype_Table extends Zend_Db_Table_Abstract {
			
	/**
	 * 
	 * @var string
	 */
	protected $_name		= 'Mimetype';
	
	/**
	 * 
	 * @var array
	 */
	protected $_primary		= array('id');
	
	
}

?>