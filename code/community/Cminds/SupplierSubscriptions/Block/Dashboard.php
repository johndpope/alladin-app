<?php
class Cminds_SupplierSubscriptions_Block_Dashboard extends Cminds_Supplierfrontendproductuploader_Block_Dashboard {
    private $_customer = null;

    /**
     * @return null|Cminds_SupplierRegistrationExtended_Model_Plan
     */
    public function getVendorPlan() {
        return Mage::helper('suppliersubscriptions')->getSupplierPlan();
    }

    /**
     * @return Mage_Customer_Model_Customer
     */
    public function getVendor() {
        if(!$this->_customer) {
            $supplier_id    = Mage::helper('supplierfrontendproductuploader')->getSupplierId();
            $this->_customer = Mage::getModel('customer/customer')->load($supplier_id);
        }

        return $this->_customer;
    }

}