<?php
class Cminds_Rma_Model_Rma_Item extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('cminds_rma/rma_item');
    }

    public function getOrderItem() {
        return Mage::getModel('sales/order_item')->load($this->getItemId());
    }

    public function getProduct() {
        $orderItem = $this->getOrderItem();
        return Mage::getModel('catalog/product')->load($orderItem->getProductId());
    }
}
