<?php

class Cminds_SupplierSubscriptions_PlanController
    extends Cminds_Supplierfrontendproductuploader_Controller_Action
{

    /**
     *
     */
    public function preDispatch() {
        parent::preDispatch();
        $hasAccess = $this->_getHelper()->hasAccess();

        if(!$hasAccess) {
            $this->getResponse()->setRedirect($this->_getHelper('supplierfrontendproductuploader')->getSupplierLoginPage());
        }
    }

    /**
     * List view.
     */
    public function listAction()
    {
        $this->_renderBlocks();
    }

    /**
     * @return Mage_Customer_Model_Customer
     */
    protected function _getCustomer()
    {
        return Mage::getSingleton('customer/session')->getCustomer();
    }

    /**
     * List view.
     */
    public function renewAction()
    {
        if (!$this->_getCustomer()->hasCurrentPlan()) {
            Mage::getSingleton('core/session')->addError(
                $this->__('You don\'t have any plan yet.')
            );
            $this->_redirect('*/*/list');
            return;
        }
        $this->_renderBlocks();
    }

    /**
     * Add plan to cart or redirect to list.
     *
     */
    public function buyAction()
    {
        $planId = $this->getRequest()->getParam('plan');

        if (is_null($planId)) {
            Mage::getSingleton('core/session')->addError(
                $this->__('No plan selected.')
            );
            $this->_redirect('*/*/list');
            return;
        }

        /* @var $planProduct Mage_Catalog_Model_Product */
        $planProduct = Mage::getModel('catalog/product')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load((int)$planId);
        $planProductPrefix = Mage::helper('supplierfrontendproductuploader')->clearString('Subscription Plan ');
        if (!$planProduct->getId() || !preg_match('/^'.$planProductPrefix.'/', $planProduct->getSku()) ) {
            Mage::getSingleton('core/session')->addError(
                $this->__('Plan does not exists.')
            );
            $this->_redirect('*/*/list');
            return;
        }

        $qty = $this->getRequest()->getParam('qty');
        if (!is_numeric($qty) || (int)$qty < 1) {
            $qty = 1;
        }

        /* @var $cart Mage_Checkout_Model_Cart */
        $cart = Mage::getSingleton('checkout/cart');
        $cart->addProduct($planProduct, array('qty'=>(int)$qty));
        $cart->save();
        Mage::getSingleton('checkout/session')->setCartWasUpdated(true);

        $this->_redirect('checkout/onepage');
        return;
    }

}