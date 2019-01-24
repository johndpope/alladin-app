<?php

class Cminds_SupplierSubscriptions_Model_Mysql4_Plan
    extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('suppliersubscriptions/plans', 'id');
    }
}