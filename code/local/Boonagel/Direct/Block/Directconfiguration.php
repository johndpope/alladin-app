<?php

class Boonagel_Direct_Block_Directconfiguration extends Mage_Core_Block_Template {

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
        
        $directConfigData = Mage::helper('Boonagel_Direct')->getConfigData();
        return $directConfigData;
    }

    public function getPaymentLogs() {
        $order = $this->getOrder();
        $incrementId = $order->getIncrementId();
        //get all the logs for this specific order
        $directpayonlinepaymentlogs = Mage::helper('Boonagel_Direct')->dynoData('direct/directlog', array('order_id,eq,' . $incrementId));
        
        return $directpayonlinepaymentlogs;
    }

    public function getErronousLogs(){
        return Mage::helper('Boonagel_Direct')->erronousPaymentLogs();
    }
    public function directCustomerLogs($flag=null) {
        
        $ordersArray = array();
        $orders = Mage::getResourceModel('sales/order_collection');
                $orders->addFieldToSelect('*');
                
                if($flag != 'admin'){
                    $orders->addFieldToFilter('customer_id', Mage::getSingleton('customer/session')->getCustomer()->getId());
                }
                
        foreach ($orders as $order) {
            $incrementId = $order->getIncrementId();
            $paymentLogs = Mage::helper('Boonagel_Direct')->dynoData('direct/directlog', array('order_id,eq,' . $incrementId));
            
            //confirm if order exists
            if($paymentLogs->count()>0){
                $ordersArray[] = $paymentLogs->getFirstItem();
            }
        }
        return $ordersArray;
    }

}
