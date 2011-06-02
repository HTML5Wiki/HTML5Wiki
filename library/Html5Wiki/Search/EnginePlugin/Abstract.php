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
 * @author Manuel Alabor <malabor@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @category Html5Wiki
 * @package Library
 * @subpackage Search
 */

/**
 * EnginePlugin abstract class
 */
abstract class Html5Wiki_Search_EnginePlugin_Abstract {

	/**
	 * Creates from an array with result data a specific model-object, fitting to
	 * this ModelEngine.
	 *
	 * @param $data Array with data
	 * @return Html5Wiki_Model_MediaVersion-instance (or child class of it)
	 */
	public function prepareModelFromData(array $data) {
		$className = $this->getModelClassName();
		$model = new $className(array('data'=>$data));
		
		return $model;
	}
	
	/**
	 * Adds model specific sql-statements to a Zend_Db_Select-instance and
	 * returns it.
	 *
	 * @param $select Zend_Db_Select
	 * @param $forTerm
	 * @return Zend_Db_Select
	 */
	public abstract function prepareSearchStatement(Zend_Db_Select $select, $forTerm);
	
	/**
	 * Returns true, if this ModelEngine can handle a specific type of MediaVersion.<br/>
	 * Type is equivalent to the Database field "mediaVersionType", which is an
	 * ENUM.
	 *
	 * @param $type
	 * @return true/false
	 */
	public abstract function canPrepareModelForType($type);
	
	/**
	 * Returns the class name of the model, which this SearchEngine searchs for.
	 *
	 * @return String with modelclass
	 */
	protected abstract function getModelClassName();
	
	/**
	 * Returns an array with indicators (strings), where a search term $forTerm
	 * in the result $result was found.
	 *
	 * @param $forTerm
	 */
	public abstract function getMatchOrigins($forTerm, $model);
	
}
?>