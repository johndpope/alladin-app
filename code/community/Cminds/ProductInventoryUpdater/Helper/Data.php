<?php

class Cminds_ProductInventoryUpdater_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function isEnabled()
    {
        $enabled = Mage::getStoreConfig(
            'supplierfrontendproductuploader_products/inventory_import/enable'
        );

        return $enabled;
    }

    /**
     * Retrieve information from config to update store admin
     * if vendor updates his stock value
     *
     * @return bool
     */
    public function canNotifyWhenCostChanged()
    {
        $enabled = Mage::getStoreConfig(
            'supplierfrontendproductuploader_products/inventory_import/notify_on_cost_change'
        );

        return $enabled;
    }

}