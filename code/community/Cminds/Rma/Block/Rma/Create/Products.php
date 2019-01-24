<?php
class Cminds_Rma_Block_Rma_Create_Products extends Cminds_Rma_Block_Rma_Abstract {
    public function _construct() {
        $this->setTemplate('cminds_rma/create/productlist.phtml');
    }

    public function getProductCollection() {
        $collection =  $this->getOrder()->getAllItems();
        foreach($collection AS $k => $item) {
            if($item->getQtyRefunded() == $item->getQtyOrdered()) {
                unset($collection[$k]);
            }
        }
        return $collection;
    }

    public function getOrder() {
        $orderId = Mage::registry('marketplace_rma_order');

        return Mage::getModel('sales/order')->load($orderId);
    }
}