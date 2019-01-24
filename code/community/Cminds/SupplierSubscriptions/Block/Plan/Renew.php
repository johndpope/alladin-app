<?php

class Cminds_SupplierSubscriptions_Block_Plan_Renew
    extends Mage_Core_Block_Template
{

    /**
     * Returns current plan product.
     *
     * @return Cminds_SupplierSubscriptions_Model_Plan
     */
    public function getCurrentPlan()
    {
        $planProduct = Mage::getModel('catalog/product/');
        /* @var $currentPlan Cminds_SupplierRegistrationExtended_Model_Plan */
        $currentPlan = Mage::getModel('suppliersubscriptions/plan');
        $customer = $this->getCustomer();
        $planId = $customer->getCurrentPlan();

        if (!is_null($planId)) {
            $currentPlan->load((int)$planId);
            $planProduct->load((int)$currentPlan->getProductId());
        }
        $currentPlan->setProduct($planProduct);

        return $currentPlan;
    }

    public function getCustomer()
    {
        return Mage::getSingleton('customer/session')->getCustomer();
    }

    /**
     * Returns action link for upgrade form.
     *
     * @return string
     */
    public function getActionUrl()
    {
        return Mage::getUrl('supplier/plan/buy');
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     * @param int $size
     * @return string
     */
    public function getHash(Mage_Catalog_Model_Product $product, $size = 5)
    {
        return substr(md5($product->getSku()), 0, $size);
    }

    public function getPlanToDate()
    {
        return date('m-d-Y', strtotime($this->getCustomer()->getPlanToDate()));
    }

    /**
     * @return array
     */
    public function getAvailableMonths()
    {
        return array(1,3,6,12);
    }

}