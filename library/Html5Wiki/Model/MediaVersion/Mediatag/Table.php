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
 * Mediaversion media tag relation table model
 */
class Html5Wiki_Model_MediaVersion_Mediatag_Table extends Zend_Db_Table_Abstract {
		
	/**
	 * 
	 * @var string
	 */
	protected $_name		= 'MediaVersionTag';
	
	protected $_rowClass	= 'Html5Wiki_Model_MediaVersion_Tag';
	
	/**
	 * 
	 * @var array
	 */
	protected $_primary		= array('tagTag', 'mediaVersionId', 'mediaVersionTimestamp');
	
	/**
	 * 
	 * @var boolean
	 */
	protected $_sequence	= false;
	
	protected $_dependentTables = array('Html5Wiki_Model_MediaVersion_Table');
	protected $_referenceMap = array(
		'MediaVersion' => array(
			'columns' => array('mediaVersionId','mediaVersionTimestamp')
			,'refTableClass' => 'Html5Wiki_Model_MediaVersion_Table'
			,'refColumns' => array('id','timestamp')
		)
	);
	
	/**
	 * Gets tags.
	 * @param int $id
	 * @param int $timestamp
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function loadTags($id, $timestamp) {
		$select = $this->select();
		$select->where('mediaVersionId = ?', $id);
		$select->where('mediaVersionTimestamp = ?', $timestamp);
		
		return $this->fetchAll($select);
	}
}
?>