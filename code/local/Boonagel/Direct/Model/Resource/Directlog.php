<?php

class Boonagel_Direct_Model_Resource_Directlog extends Mage_Core_Model_Resource_Db_Abstract{

	protected function _construct(){
		$this->_init('direct/directlog','id');
	}

}
