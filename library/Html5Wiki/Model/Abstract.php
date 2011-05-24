<?php
/**
 * This file is part of the HTML5Wiki Project.
 *
 * @author		Nicolas Karrer <nkarrer@hsr.ch>
 * @copyright	(c) HTML5Wiki Team 2011
 * @package		Html5Wiki
 * @subpackage	Library
 */
 
abstract class Html5Wiki_Model_Abstract extends Zend_Db_Table_Row_Abstract {

	/**
	 * Try to call getter of given column. If not available do the default handling.
	 *
	 * @param	String		$columnName
	 * @return	Mixed
	 */
	public function __get($columnName) {
		$methodName	= 'get' . ucfirst($columnName);
		if( method_exists($this, $methodName) ) {
			return call_user_func(array($this, $methodName));
		} else {
			return parent::__get($columnName);
		}
	}
}
