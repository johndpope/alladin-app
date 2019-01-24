<?php

/**
 * Class Cminds_OrderSplit_Helper_Data
 */
class Cminds_OrderSplit_Helper_Data extends Mage_Core_Helper_Abstract
{

    /**
     * Check if enabled by admin.
     *
     * @return bool
     */
    public function isEnabled()
    {
        $config = Mage::getStoreConfig('marketplace_order_split_configuration/general/enabled');

        return $config;
    }
}
