<?php

require_once 'Cminds/Supplierfrontendproductuploader/controllers/Adminhtml/Supplier/ProductController.php';
class Cminds_SupplierSubscriptions_Adminhtml_Supplier_ProductController extends Cminds_Supplierfrontendproductuploader_Adminhtml_Supplier_ProductController
{
    public function approveAction()
    {

        if (Mage::helper('suppliersubscriptions')->isEnabled()) {
            if (!$this->canApproveProduct()) {
                return;
            }
        }
        parent::approveAction();
    }

    protected function canApproveProduct()
    {
        $productId = $this->_request->getParam('id');
        $creatorId = Mage::getModel('catalog/product')->load($productId)->getCreatorId();
        $creator = Mage::getModel('customer/customer')->load($creatorId);
        $planId = $creator->getCurrentPlan();
        $supplierPlan = Mage::getModel('suppliersubscriptions/plan')->load((int)$planId);

        $productsPlanCount = Mage::helper('suppliersubscriptions')->getPlanCountsAdmin($creatorId);
        $suppliersCount = Mage::helper('suppliersubscriptions')->getProductCountsAdmin($creatorId);

        if (!is_null($supplierPlan)) {
            $supplier = $creator;
            $planToData = strtotime($supplier->getPlanToDate());
            if ($planToData != false && time() <= $planToData) {
                $planActive = true;
            } else {
                $planActive = false;
            }
        }

        if ($planActive && $productsPlanCount > $suppliersCount) {
            return true;
        }
        if (!$planActive) {
            Mage::getSingleton('core/session')->addError($this->__("Suppliers plan is not active"));
        } else {
            Mage::getSingleton('core/session')->addError(
                $this->__("The products amount limit has been reached by this supplier.")
            );
        }
        Mage::app()->getResponse()->setRedirect(Mage::helper('adminhtml')->getUrl("*/*/index"));
        return false;
    }
}
