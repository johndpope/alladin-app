<?php

class Boonagel_Alpesa_Model_Resource_Alpesauser extends Mage_Core_Model_Resource_Db_Abstract{

	protected function _construct(){
		$this->_init('alpesa/alpesauser','id');
	}

}
