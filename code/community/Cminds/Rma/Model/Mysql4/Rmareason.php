<?php
class Cminds_Rma_Model_Mysql4_Rmareason extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('cminds_rma/rma_reason', 'entity_id');
    }
}
