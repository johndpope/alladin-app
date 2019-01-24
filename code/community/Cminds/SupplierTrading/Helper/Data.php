<?php
class Cminds_Suppliertrading_Helper_Data extends Mage_Core_Helper_Abstract {

    public function isEnabled()
    {
        return Mage::getStoreConfig("suppliertrading/general/module_enabled");
    }
}
