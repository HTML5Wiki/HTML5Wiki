<?php
/**
 * This file is part of the HTML5Wiki Project.
 *
 * @author		Nicolas Karrer <nkarrer@hsr.ch>
 * @copyright	(c) HTML5Wiki Team 2011
 * @package		Html5Wiki
 * @subpackage	Library
 */

class Html5Wiki_Model_User_Table extends Zend_Db_Adapter_Abstract {
			
	/**
	 * 
	 * @var string
	 */
	protected $_name		= 'User';
	
	/**
	 * 
	 * @var array
	 */
	protected $_primary		= array('id');
	
		
}
?>