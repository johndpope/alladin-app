<?php

class Boonagel_Cba_Model_Resource_Cbalog extends Mage_Core_Model_Resource_Db_Abstract{

	protected function _construct(){
		$this->_init('cba/cbalog','id');
	}

}
