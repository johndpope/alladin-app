<?php
class Cminds_Pickuptime_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function isEnabled()
    {
        return Mage::getStoreConfig('supplierfrontendproductuploader_products/pickup_time/pickuptime_enable');
    }

    public function getExtensionVersion()
    {
        return (string) Mage::getConfig()->getNode()->modules->Cminds_Pickuptime->version;
    }
}
