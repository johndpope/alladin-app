<?php
class Cminds_Rma_Model_Mysql4_Rma_Status extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('cminds_rma/rma_status', 'entity_id');
    }
}
