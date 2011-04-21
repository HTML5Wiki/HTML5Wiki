<?php

abstract class Html5Wiki_Model_Abstract implements ArrayAccess {
	
	/**
	 * 
	 * @var Array
	 */
	protected $data = array();
	
	/**
	 * Fallback for direct member access.
	 * First it checks for a getter function, if not available try to find the data in $this->data
	 *
	 * @param	String		$memberName
	 * @return 	String
	 */
	public function __get($memberName) {
		$dataKey	= strtolower($memberName);
		$methodName	= 'get' . $memberName;

		if( method_exists($this, $methodName) ) {
			return call_user_func(array($this, $methodName));
		} elseif( array_key_exists($dataKey, $this->data) ) {
			return $this->data[$dataKey];
		}
		
		return '';
	}
	
	/**
	 * Array access function to check if an attribute
	 * is set in the internal record storage
	 *
	 * Usage: $obj = new Obj(); isset($obj['id_person'])
	 *
	 * @magic
	 * @param	String		$name
	 * @return	Boolean
	 */
	public function offsetExists($name) {
		return isset($this->data[$name]);
	}

	/**
	 * Array access function to delete an attribute
	 * in the internal record storage
	 *
	 * Usage: $obj = new Obj(); unset($obj['id_person'])
	 *
	 * @magic
	 * @param	String		$name
	 */
	public function offsetUnset($name) {
		unset($this->data[$name]);
	}

	/**
	 * Array access function to set an attribute
	 * in the internal record storage
	 *
	 * Usage: $obj = new Obj(); $obj['id_person'] = 53;
	 *
	 * @magic
	 * @param	String		$name
	 * @param	String		$value
	 */
	public function offsetSet($name, $value) {
		$this->data[$name] = $value;
	}

	/**
	 * Array access function to get an attribute
	 * from the internal record storage
	 *
	 * Usage: $obj = new Obj(); echo $obj['id_person'];
	 *
	 * @magic
	 * @param	String		$name
	 * @return	String
	 */
	public function offsetGet($name) {
		return $this->get($name);
	}
}
?>