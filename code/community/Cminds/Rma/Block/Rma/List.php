<?php
class Cminds_Rma_Block_Rma_List extends Cminds_Rma_Block_Rma_Abstract {
    public function _construct() {
        $this->setTemplate('cminds_rma/list.phtml');
    }

    public function getEntries() {
        $collection = Mage::getModel('cminds_rma/rma')
            ->getCollection();

        $collection->addFieldToFilter('status_id', array('neq' => Cminds_Rma_Model_Rma::DEFAULT_CANCELED_ID));

        if(Mage::getSingleton('customer/session')->isLoggedIn()) {
            $customer = Mage::getSingleton('customer/session')->getCustomer();
            $collection->addFieldToFilter('customer_id', array('eq' => $customer->getId()));
        }

        return $collection;
    }

}