<?php

class Boonagel_Cba_Block_Cbaconfiguration extends Mage_Core_Block_Template {

    protected $order;

    public function getOrder() {
        if (is_null($this->order)) {
            if (Mage::registry('current_order')) {
                $order = Mage::registry('current_order');
            } elseif (Mage::registry('order')) {
                $order = Mage::registry('order');
            } else {
                $order = new Varien_Object();
            }
            $this->order = $order;
        }
        return $this->order;
    }

    public function configData() {
        
        $cbaConfigData = Mage::helper('Boonagel_Cba')->getConfigData();
        return $cbaConfigData;
    }

    public function getPaymentLogs() {
        $order = $this->getOrder();
        $incrementId = $order->getIncrementId();
        //get all the logs for this specific order
        $mpesaPaymentLogs[0] = Mage::helper('Boonagel_Cba')->dynoData('cba/cbalog', array('order_id,eq,' . $incrementId));
        $mpesaPaymentLogs[1] = $order;
        return $mpesaPaymentLogs;
    }

    public function getErronousLogs(){
        return Mage::helper('Boonagel_Cba')->erronousPaymentLogs();
    }
    public function cbaCustomerLogs($flag=null) {
        $customerId = Mage::getSingleton('customer/session')->getCustomer()->getId();
        //$ordersIncrementIds = array();
        $ordersArray = array();
        $orders = Mage::getResourceModel('sales/order_collection');
                $orders->addFieldToSelect('*');
                
                if($flag != 'admin'){
                    $orders->addFieldToFilter('customer_id', Mage::getSingleton('customer/session')->getCustomer()->getId());
                }
                
        foreach ($orders as $order) {
            $orderStdClass = new stdClass();
            $incrementId = $order->getIncrementId();
            $paymentLogs = Mage::helper('Boonagel_Cba')->dynoData('cba/cbalog', array('order_id,eq,' . $incrementId));
            
            //add flag to the array if payment logs exist
            $orderStdClass->order = $order;
            if($paymentLogs->count()>0){
                $orderStdClass->logs = $paymentLogs;
                $orderStdClass->paymentLogs = true;
            }else{
                $orderStdClass->paymentLogs = false;
            }
            $ordersArray[] = $orderStdClass;
        }
        return $ordersArray;
    }

}
