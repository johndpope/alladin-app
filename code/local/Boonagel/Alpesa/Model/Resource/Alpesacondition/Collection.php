
<?php

class Boonagel_Alpesa_Model_Resource_Alpesacondition_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract{

	protected function _construct(){
		$this->_init('alpesa/alpesacondition');
	}

		public function addGroupByConfigFilter()
{

    $this->getSelect()->group('main_table.config_id');
    return $this;
}

}
