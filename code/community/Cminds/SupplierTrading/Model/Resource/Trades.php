<?php

class Cminds_SupplierTrading_Model_Resource_Trades
    extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('suppliertrading/trades', 'id');
    }


}
