<?php

class Boonagel_Direct_Helper_Data extends Mage_Core_Helper_Abstract {
  
    /*     * get the static configuration data* */
    //Mage::getStoreConfig('payment/directpayonline/active');

    function configDetails() {

        $configDetails['active'] = Mage::getStoreConfig('payment/directpayonline/active');
        $configDetails['title'] = Mage::getStoreConfig('payment/directpayonline/title');
        $configDetails['company_token'] = Mage::getStoreConfig('payment/directpayonline/company_token');
        $configDetails['gateway_url'] = Mage::getStoreConfig('payment/directpayonline/gateway_url');
        $configDetails['ptl_type'] = Mage::getStoreConfig('payment/directpayonline/ptl_type');
        $configDetails['ptl'] = Mage::getStoreConfig('payment/directpayonline/ptl');
        $configDetails['service_type'] = Mage::getStoreConfig('payment/directpayonline/service_type');
        $configDetails['aramex_url'] = Mage::getStoreConfig('payment/directpayonline/aramex_url');
        $configDetails['aramex_secret'] = Mage::getStoreConfig('payment/directpayonline/aramex_secret');
        
        return $configDetails;

    }

    function lastRealOrderId(){
        return Mage::getSingleton('checkout/session')->getLastRealOrderId();
    }
    function payBill() {
        return "880886";
    }
    
    function getCustomerContacts(){
        $custContacts[0] = "0791888881";
        $custContacts[1] = "customercare@alladin.co.ke";
        return $custContacts;
    }
    
    function storeId(){
       return Mage::app()->getStore()->getId(); 
    }
    function logoSource(){
        return Mage::getSingleton('core/design_package')->getSkinBaseUrl().Mage::getStoreConfig('design/header/logo_src',$this->storeId());
    }

    function orderObjectGet($incrementId){
        $order = new Mage_Sales_Model_Order();
        $order->loadByIncrementId($incrementId);
         return $order->getData();
    }
    
    function salesOrderObject($orderId){
        return Mage::getModel('sales/order')->load($orderId);
    }
    
    function aramexGateway(){
        $data = array();
        $data['url'] = "http://127.0.0.1:8012/OneDrive/alladin/cba/payments/aramex";
        $data['secret'] = "aramexsecretwithus";
        return $data;
    }
    
    function erronousPaymentLogs(){
        return Mage::helper('Boonagel_Direct')->dynoData('cba/cbalog', array('erronous,eq,1'));
    }
    function dynoData($modelType, $arrayConditions = null, $limit = null, $orderArray = null) {

        $dynamicDatad = Mage::getModel($modelType)->getCollection();

        if (count($arrayConditions) > 0) {
            foreach ($arrayConditions as $arrayCondition) {
                $arrayedVals = explode(",", $arrayCondition);
                $dynamicDatad->addFieldToFilter($arrayedVals[0], array($arrayedVals[1] => $arrayedVals[2]));
            }
        }

        if ($orderArray != null) {
            $dynamicDatad->setOrder($orderArray[0], $orderArray[1]);
        }

        if ($limit != null) {
            $dynamicDatad->setPageSize((int) $limit);
        }

        return $dynamicDatad;
    }


    /*     * get data in relation to customer id or not id at all * */



    function formatNumber($value, $decimalPlaces = 0) {
        //never
        if (!is_int($decimalPlaces)) {
            $decimalPlaces = 0;
        }
        return number_format($value, $decimalPlaces);
    }

    function getCurrentCurrency() {
        return Mage::app()->getStore()->getCurrentCurrencyCode();
    }


    /*     * set the title of the page* */
    public function setTitle($currentContext, $title) {
        //never

        $currentContext->getLayout()->getBlock('head')->setTitle($title);
    }

}
