<?php
/**
 * This file is part of the HTML5Wiki Project.
 *
 * @author		Nicolas Karrer <nkarrer@hsr.ch>
 * @copyright	Html5Wiki 2011
 * @package		Html5Wiki
 * @subpackage	Library
 */

/**
 * Defining of the Media table
 * 
 * @author Nicolas Karrer
 */
class Html5Wiki_Model_Media_Table extends Zend_Db_Table_Abstract {
	
	/**
	 * 
	 * @var string
	 */
	protected $_name		= 'MediaVersion';
	
	/**
	 * 
	 * @var	array
	 */
	protected $_primary		= array('id', 'timestamp');
	
	/**
	 * 
	 * @var boolean
	 */
	protected $_sequence	= false;
}


?>