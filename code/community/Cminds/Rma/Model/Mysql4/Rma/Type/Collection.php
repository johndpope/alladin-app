<?php
class Cminds_Rma_Model_Mysql4_Rma_Type_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('cminds_rma/rma_type');
    }

    public function toOptionArray() {
        $options = array();

        foreach($this->getItems() AS $item) {
            $options[$item->getId()] = $item->getName();
        }

        return $options;
    }

}
