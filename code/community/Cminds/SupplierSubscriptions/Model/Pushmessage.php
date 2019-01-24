<?php

class Cminds_SupplierSubscriptions_Model_Pushmessage
    extends Mage_Core_Model_Abstract
{
    private $_vendor;

    protected function _construct()
    {
        $this->_init('suppliersubscriptions/pushmessage');
    }

//    protected function _beforeSave()
//    {
//        parent::_beforeSave();
//        if($this->getIsApproved()) {
//            if($this->getAvailable() > 0 && $this->getMessageAwaitingForApproval()) {
//                $this->setMessage($this->getMessageAwaitingForApproval());
//                $this->setMessageAwaitingForApproval(NULL);
//                $this->setSentTimes($this->getSentTimes() + 1);
//            }
//        }
//    }
//
//    protected function _afterSave()
//    {
//        parent::_afterSave();
//        if($this->getIsApproved()) {
//            $collection = Mage::getModel('supplierregistrationextended/pushmessagesent')->getCollection()->addFieldToFilter('vendor_id', $this->getVendorId());
//
//            foreach ($collection AS $item) {
//                $item->setMessage($this->getMessage());
//                $item->save();
//            }
//        }
//    }
//
//    public function send($customer) {
//        try {
//            if(!$this->isValidForSend()) throw new Exception("Cannot send message to " . $customer->getId());
//
//            $store = Mage::app()->getStore()->getId();
//            $vendor = Mage::getModel('customer/customer')->load($this->getVendorId());
//
//            if($customer) {
//                $senderData = array(
//                    'name' => $vendor->getName(),
//                    'email' => $vendor->getEmail()
//                );
//            } else {
//                $senderData = 'general';
//            }
//
//            $s = Mage::getModel('core/email_template')
//                ->sendTransactional('push_message_container', $senderData, $customer->getEmail(), $customer->getName(), array('content' => $this->getMessage()), $store);
//
//            // if($s->getSentSuccess()) {
//            Mage::getModel('supplierregistrationextended/pushmessagesent')
//                ->setCustomerId($customer->getId())
//                ->setVendorId($this->getVendorId())
//                ->setMessage($this->getMessage())
//                ->setCreatedAt(date('Y-m-d H:i:s'))
//                ->save();
//            // }
//        } catch (Exception $e) {
//            Mage::log($e->getMessage());
//        }
//    }
//
//    public function countSentMessages() {
//        return $this->getSentTimes();
//    }
//
//    public function isValidForSend() {
//        return $this->getMessage();
//    }
//
//    public function getAvailable() {
//        $plan = Mage::getModel('supplierregistrationextended/plan')->load($this->_getVendor()->getCurrentPlan());
//        return $plan->getPushMessages() - $this->countSentMessages();
//    }
//
//    private function _getVendor() {
//        if(!$this->_vendor) {
//            if(!$this->getVendorId()) {
//                $vendor_id = Mage::getSingleton('customer/session')->getCustomer()->getId();
//            } else {
//                $vendor_id = $this->getVendorId();
//            }
//
//            $this->_vendor = Mage::getModel('customer/customer')->load($vendor_id);
//        }
//
//        return $this->_vendor;
//    }
}