<?php

class Cminds_SupplierSubscriptions_Model_Observer extends Mage_Core_Model_Abstract
{

    public function onCustomerSaveBefore(Varien_Event_Observer $observer)
    {
        /**
         * get params from edit form in admin panel
         */
        if (Mage::helper('suppliersubscriptions')->isEnabled()) {
            $customer = $observer->getEvent()->getCustomer();
            $currentPlan = Mage::app()->getRequest()->getParam('current_plan');
            $planFromDate = Mage::app()->getRequest()->getParam('plan_from_date');
            $planToDate = Mage::app()->getRequest()->getParam('plan_to_date');

            if (!empty($currentPlan)) {
                $customer->setCurrentPlan($currentPlan);
                $customer->setPlanFromDate($planFromDate);
                $customer->setPlanToDate($planToDate);
            }
        }
    }

    /**
     * Check is supplier is approved.
     *
     * Event: customer_login
     * @param Varien_Event_Observer $observer
     */
    public function onCustomerLogin(Varien_Event_Observer $observer)
    {
        if (Mage::helper('suppliersubscriptions')->isEnabled()) {
            $customer = $observer->getEvent()->getCustomer();
            $customerId = $customer->getId();
            $customer = Mage::getModel('customer/customer')->load($customerId);
            $defaultPlan = Mage::getStoreConfig('suppliersubscriptions_catalog/general/default_plan');

            if (!Mage::helper('suppliersubscriptions')->isSupplier($customer->getId())) {
                return;
            }

            if (!$customer->getCurrentPlan()) {
                $planToDate = date('Y-m-d H:i:s', strtotime("+9 months"));

                $customer->setPlanFromDate(date('Y-m-d H:i:s'))
                    ->setPlanToDate($planToDate)
                    ->setCurrentPlan($defaultPlan)
                    ->save();
            }
        }
    }

    public function onOrderPlaceAfter(Varien_Event_Observer $observer)
    {
        if (Mage::helper('suppliersubscriptions')->isEnabled()) {
            /* @var $order Mage_Sales_Model_Order */
            $order = $observer->getEvent()->getOrder();
            $productPlan = array();

            $plansCollection = Mage::getModel('suppliersubscriptions/plan')->getCollection();
            foreach ($plansCollection as $plan) {
                /* @var $plan Cminds_SupplierRegistrationExtended_Model_Plan */
                $productPlan[$plan->getProductId()] = $plan;
            }

            /**
             * Return if there is no plans in system.
             */
            if (empty($productPlan)) {
                return;
            }

            foreach ($order->getItemsCollection() as $item) {
                /* @var $item Mage_Sales_Model_Order_Item */
                if (isset($productPlan[$item->getProductId()])) {
                    $qty = (int)$item->getQtyOrdered();
                    $customer = Mage::getModel('customer/customer')->load($order->getCustomerId());
                    /* @var $currentPlan Cminds_SupplierRegistrationExtended_Model_Plan */
                    $currentPlan = $productPlan[$item->getProductId()];
                    $time = time();
                    if ((int)$customer->getCurrentPlan() == (int)$currentPlan->getId()) {
                        $time = strtotime($customer->getPlanToDate());
                    }
                    $planToDate = date('Y-m-d H:i:s', strtotime("+{$qty} month", $time));

                    $customer->setPlanFromDate(date('Y-m-d H:i:s'))
                        ->setPlanToDate($planToDate)
                        ->setCurrentPlan($currentPlan->getId())
                        ->save();
                    return;
                }
            }
        }
    }

    public function navLoad($observer)
    {
        $event = $observer->getEvent();
        $items = $event->getItems();
        $helper = Mage::helper('suppliersubscriptions');
        if ($helper->isEnabled()) {
            $items['RENEW'] =  [
                'label'     => $helper->__('Renew'),
                'url' => 'supplier/plan/renew',
                'parent'    => null,
                'action_names' => [
                    'cminds_supplierfrontendproductuploader_plan_renew',
                ],
                'sort'     => 3.1
            ];

            $items['UPGRADE'] =  [
                'label'     => $helper->__('Upgrade'),
                'url' => 'supplier/plan/list',
                'parent'    => null,
                'action_names' => [
                    'cminds_supplierfrontendproductuploader_plan_list',
                ],
                'sort'     => 3.2
            ];
        }
        $observer->getEvent()->setItems($items);
    }

    public function validateSubscriptionPlan(Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent();

        if (!$this->validateSupplierAction($event)
            && !$this->validateMarketplaceAction($event)
        ) {
            return $this;
        }

        $helper = Mage::helper('suppliersubscriptions');

        if (!$helper->isEnabled()) {
            return $this;
        }

        $planProductCount = $helper->getSupplierPlanProducts();
        $productCount = $helper->getSupplierProductsCount();
        $planActive = $helper->isSupplierPlanActive();

        if ($planActive && $planProductCount > $productCount) {
            return $this;
        }
        if (!$planActive) {
            $message = $helper->__("Your plan is deactivated.");
        } else {
            $message = $helper->__("The products amount limit has been reached.");
        }

        Mage::getSingleton('core/session')->addError($message);
        Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl('supplier/'));
        Mage::app()->getResponse()->sendResponse();
        exit;
    }

    public function validateSupplierAction($event)
    {
        $route = $event->getControllerAction()->getRequest()->getRouteName();
        $controllerName = $event->getControllerAction()->getRequest()->getControllerName();
        $actionName = $event->getControllerAction()->getRequest()->getActionName();

        if ($route != 'cminds_supplierfrontendproductuploader') {
            return false;
        }

        if ($controllerName != 'products') {
            return false;
        }

        if (!in_array($actionName, array('create', 'chooseType', 'clone'))) {
            return false;
        }

        return true;
    }

    public function validateMarketplaceAction($event)
    {
        $route = $event->getControllerAction()->getRequest()->getRouteName();
        $controllerName = $event->getControllerAction()->getRequest()->getControllerName();
        $actionName = $event->getControllerAction()->getRequest()->getActionName();

        if ($route != 'cminds_marketplace') {
            return false;
        }

        if ($controllerName != 'import') {
            return false;
        }

        if (!in_array($actionName, array('products'))) {
            return false;
        }

        return true;
    }

    public function handleUpload()
    {
        $supplierSubscriptions = Mage::helper('suppliersubscriptions')->isEnabled();

        if ($supplierSubscriptions) {
            $helper = Mage::helper('suppliersubscriptions');
            $planProductCount = $helper->getSupplierPlanProducts();
            $productCount = $helper->getSupplierProductsCount();

            $i = 0;
            $handle = fopen($_FILES['file']['tmp_name'], "r");
            $newProductsCount = 0;
            while (($data = fgetcsv($handle)) !== false) {
                if ($i!=0) {
                    if (empty($data[0])) {
                        $newProductsCount++;
                    }
                }
                $i++;
            }

            if ($supplierSubscriptions && $newProductsCount > ($planProductCount - $productCount)) {
                Mage::getSingleton('core/session')->addError(
                    $helper->__("Too many products added to import for current plan.")
                );
                throw new Exception();
            }
        }
    }
}
