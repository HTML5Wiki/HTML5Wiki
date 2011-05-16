<?php
/**
 * This file is part of the HTML5Wiki Project.
 *
 * @author		Nicolas Karrer <nkarrer@hsr.ch>
 * @author      Michael Weibel <mweibel@hsr.ch>
 * @copyright	(c) HTML5Wiki Team 2011
 * @package		Html5Wiki
 * @subpackage	Library
 */

class Html5Wiki_Model_MediaVersion_Mediatag_Tag_Table extends Zend_Db_Table_Abstract {
		
	/**
	 * 
	 * @var string
	 */
	protected $_name		= 'Tag';
	
	/**
	 * 
	 * @var array
	 */
	protected $_primary		= array('tag');
	
	/**
	 * 
	 * @var boolean
	 */
	protected $_sequence	= false;
	
	protected $_dependentTables = array('Html5Wiki_Model_MediaVersion_Mediatag_Table');
	protected $_referenceMap = array(
		'MediaVersionTag' => array(
			'columns' => array('tag'),
			'refTableClass' => 'Html5Wiki_Model_MediaVersion_Mediatag_Table',
			'refColumns' => array('tag')
		)
	);
}
?>