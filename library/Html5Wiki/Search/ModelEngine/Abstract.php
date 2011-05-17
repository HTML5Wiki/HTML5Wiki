<?php
/**
 * ModelEngine abstract class
 * Subclass to implement specific domain knowledge for searching different
 * MediaVersion-types.
 *
 * @author Manuel Alabor <malabor@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @package Library
 * @subpackage SearchEngine
 */
abstract class Html5Wiki_Search_ModelEngine_Abstract {

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
	
}
?>