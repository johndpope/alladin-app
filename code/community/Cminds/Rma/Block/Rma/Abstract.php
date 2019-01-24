<?php
abstract class Cminds_Rma_Block_Rma_Abstract extends Mage_Core_Block_Template {
    protected function getLoggedCustomer() {
        return Mage::getSingleton('customer/session')->getCustomer();
    }

    protected function getRmaId() {
        return Mage::registry('marketplace_rma');
    }

    public function getOrderId($entry)
    {
        return $entry->getOrder()->getIncrementId();
    }
}