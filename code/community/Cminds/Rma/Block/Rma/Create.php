<?php
class Cminds_Rma_Block_Rma_Create extends Cminds_Rma_Block_Rma_Abstract {
    public function _construct() {
        $this->setTemplate('cminds_rma/create.phtml');
    }

    public function getOrderCollection() {
        return Mage::getModel('sales/order')
            ->getCollection()
            ->addFieldToFilter('status', "complete")
            ->addFieldToFilter('customer_id', $this->getLoggedCustomer()->getId());
    }

    public function getReasonCollection() {
        return Mage::getModel('cminds_rma/rma_reason')->getCollection()->setOrder('sort_order', Cminds_Rma_Model_Rma::DEFAULT_SORT);
    }

    public function getTypeCollection() {
        return Mage::getModel('cminds_rma/rma_type')->getCollection()->setOrder('sort_order', Cminds_Rma_Model_Rma::DEFAULT_SORT);
    }

    public function getIsOpened() {
        return Mage::getModel('cminds_rma/rma_opened')->toOptionArray();
    }
}