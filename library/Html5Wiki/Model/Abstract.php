<?php
/**
 * This file is part of the HTML5Wiki Project.
 * 
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
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
 * Abstract Model
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
