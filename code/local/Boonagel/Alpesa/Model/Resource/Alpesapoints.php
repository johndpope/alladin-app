<?php

class Boonagel_Alpesa_Model_Resource_Alpesapoints extends Mage_Core_Model_Resource_Db_Abstract{

	protected function _construct(){
		$this->_init('alpesa/alpesapoints','id');
	}

	/**public function getSumActualPoints()
	{
    $select = $this->getReadConnection()
        ->select()
        ->from($this->getMainTable(), array('sum' => new Zend_Db_Expr('SUM(points)')));

    return $this->getReadConnection()->fetchOne($select);
	}**/

}
