<?php
class Cminds_SupplierRedirection_Model_Adminhtml_Observer
{
    private $currentUrl = '';
    private $newUrl = '';

    public function onCustomerSave($event)
    {
        $customer = $event->getCustomer();

        if (!Mage::helper('supplierfrontendproductuploader')->isSupplier($customer->getId())) {
            return false;
        }
        try {
            $postData = Mage::app()->getRequest()->getPost();
            $transaction = Mage::getModel('core/resource_transaction');


            $this->currentUrl = $customer->getDomainUrl();
            $this->newUrl = $postData['domain']['url'];

            $customer->setDomainUrl($this->newUrl);
            $transaction->addObject($customer);
            $transaction->save();
        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addError($e->getMessage());
            Mage::log($e->getMessage());
        }
    }
}