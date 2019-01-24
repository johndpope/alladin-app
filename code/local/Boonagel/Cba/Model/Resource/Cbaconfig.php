<?php

class Boonagel_Cba_Model_Resource_Cbaconfig extends Mage_Core_Model_Resource_Db_Abstract{

	protected function _construct(){
		$this->_init('cba/cbaconfig','id');
	}

}
