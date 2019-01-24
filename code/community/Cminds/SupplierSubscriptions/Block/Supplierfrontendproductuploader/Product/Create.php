<?php

class Cminds_SupplierSubscriptions_Block_Supplierfrontendproductuploader_Product_Create
    extends Cminds_Supplierfrontendproductuploader_Block_Product_Create
{
    protected $_pendingData;
    /**
     * @return int
     */
    public function getMaxImagesCount()
    {
        $customerId = Mage::getSingleton('customer/session')->getCustomer()->getId();
        $customer = Mage::getModel('customer/customer')->load($customerId);
        $planId = $customer->getCurrentPlan();
        $imagesPerProduct = Mage::getModel('suppliersubscriptions/plan')->load($planId)->getImagesPerProduct();

        return $imagesPerProduct;
    }
}