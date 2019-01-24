<?php

class Cminds_SupplierSubscriptions_Model_Adminhtml_Observer
{
    public function onProductSaveBefore($observer)
    {
        $productData = $observer->getProduct()->getData();

        $productOrigData = $observer->getProduct()->getOrigData();
        if (isset($productData['creator_id']) && !empty($productData['creator_id'])) {
            if ($productData['frontendproduct_product_status'] == 1
                && $productOrigData['frontendproduct_product_status']
                != $productData['frontendproduct_product_status']
            ) {
                $creatorId = $productData['creator_id'];
                $creator = Mage::getModel('customer/customer')->load($creatorId);
                $planId = $creator->getCurrentPlan();
                $supplierPlan = Mage::getModel('suppliersubscriptions/plan')->load((int)$planId);

                $productsPlanCount = Mage::helper('suppliersubscriptions')->getPlanCountsAdmin($creatorId);
                $suppliersCount = Mage::helper('suppliersubscriptions')->getProductCountsAdmin($creatorId);

                $planActive = false;
                if (!is_null($supplierPlan)) {
                    $supplier = $creator;
                    $planToData = strtotime($supplier->getPlanToDate());
                    if ($planToData != false && time() <= $planToData) {
                        $planActive = true;
                    }
                }

                if ($planActive && $productsPlanCount >= $suppliersCount) {
                    return true;
                }

                if (!$planActive) {
                    Mage::getSingleton('core/session')
                        ->addError("Suppliers plan is not active");
                } else {
                    Mage::getSingleton('core/session')
                        ->addError("The products amount limit has been reached by this supplier.");
                }
                Mage::app()->getResponse()->setRedirect(Mage::helper('adminhtml')->getUrl("*/*/index"));
                throw new Exception('', 1022);
                return;
            }
        }
    }
}