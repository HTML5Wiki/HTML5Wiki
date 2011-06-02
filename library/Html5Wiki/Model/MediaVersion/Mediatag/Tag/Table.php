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
 * @author Nicolas Karrer <nkarrer@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @category Html5Wiki
 * @package Library
 * @subpackage Model
 */

/**
 * Mediaversion mediatag tag table
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