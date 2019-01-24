<?php
class Cminds_Pickuptime_Model_Mysql4_Excluded_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('cminds_pickuptime/excluded');
    }

    public function toJson() {
        $arrayOfItems = array();

        foreach($this->getItems() AS $item) {
            $arrayOfItems[] = $item->getData();
        }

        return json_encode($arrayOfItems);
    }
}
