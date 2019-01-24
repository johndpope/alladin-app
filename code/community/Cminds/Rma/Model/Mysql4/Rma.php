<?php
class Cminds_Rma_Model_Mysql4_Rma extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('cminds_rma/rma_entity', 'entity_id');
    }
}
