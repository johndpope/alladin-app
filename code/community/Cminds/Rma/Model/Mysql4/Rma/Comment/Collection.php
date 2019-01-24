<?php
class Cminds_Rma_Model_Mysql4_Rma_Comment_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('cminds_rma/rma_comment');
    }
}
