<?php
/**
 * Created by JetBrains PhpStorm.
 * User: nicolas
 * Date: 23.05.11
 * Time: 13:22
 * To change this template use File | Settings | File Templates.
 */
 
class Test_Unit_Library_Model_MediaVersion_FakeTable extends Html5Wiki_Model_MediaVersion_Table {

	public function saveMediaVersion($saveData) {
		return $this->insert($saveData);
	} 

}
