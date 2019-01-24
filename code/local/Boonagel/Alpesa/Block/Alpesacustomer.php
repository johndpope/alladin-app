<?php

class Boonagel_Alpesa_Block_Alpesacustomer extends Mage_Core_Block_Template {

    public function reedemData() {

        //config data,wallet data,reedem history log
        $arrayedData = array();
        $configData = Mage::helper('Boonagel_Alpesa')->getConfigData();
        $arrayedData[0] = $configData;

        $customerId = Mage::getSingleton('customer/session')->getCustomer()->getId();
        $walletData = Mage::helper('Boonagel_Alpesa')->dynoData('alpesa/alpesawallet', array('user_id,eq,' . $customerId), 1);
        $arrayedData[1] = $walletData;

        $reedemData = Mage::helper('Boonagel_Alpesa')->dynoData('alpesa/alpesaredeem', array('user_id,eq,' . $customerId), null, array('created_at', 'DESC'));
        $arrayedData[2] = $reedemData;

        return $arrayedData;
    }

    public function walletData() {

        $arrayedData = array();
        $customerId = Mage::getSingleton('customer/session')->getCustomer()->getId();

        //wallet data
        $walletData = Mage::helper('Boonagel_Alpesa')->dynoData('alpesa/alpesawallet', array('user_id,eq,' . $customerId), 1);
        $arrayedData[0] = $walletData;

        //invoice used data
        $usedAmtData = Mage::helper('Boonagel_Alpesa')->dynoData('alpesa/alpesainvoice', array('user_id,eq,' . $customerId));
        $arrayedData[1] = $usedAmtData;

        //card data
        $cardData = Mage::helper('Boonagel_Alpesa')->dynoData('alpesa/alpesacard');
        $arrayedData[2] = $cardData;

        //all alpesa available points
        $availablePointsData = Mage::helper('Boonagel_Alpesa')->dynoData('alpesa/alpesapoints', array('user_id,eq,' . $customerId, 'status,eq,0'));
        $arrayedData[3] = $availablePointsData;

        return $arrayedData;
    }

    public function getCardTypeAttr() {

        $cardAttr = array();
        $customerId = Mage::getSingleton('customer/session')->getCustomer()->getId();

        $walletData = Mage::helper('Boonagel_Alpesa')->dynoData('alpesa/alpesawallet', array('user_id,eq,' . $customerId), 1);
        $cardData = Mage::helper('Boonagel_Alpesa')->dynoData('alpesa/alpesacard');
        if (count($walletData) < 1 || count($cardData) < 1) {
            return $cardAttr;
        }
        $walletDatad = $walletData->getFirstItem();
        $actualPoints = count($walletDatad) == 1 ? $walletDatad->actual_points : 0;
        foreach ($cardData as $cardDatad) {
            $arrayedCard = explode(",", $cardDatad->card_min_max_points);
            if ($actualPoints > $arrayedCard[0] && $actualPoints < $arrayedCard[1]) {
                $cardAttr['cardColor'] = $cardDatad->card_color;
                $cardAttr['cardName'] = $cardDatad->card_name;
                $cardAttr['cardVoucherAmt'] = $cardDatad->card_voucher_amount;
                $cardAttr['cardId'] = $cardDatad->id;
                $cardAttr['cardDiscount'] = $cardDatad->card_discount;
                $cardAttr['cardGiftDate'] = $cardDatad->card_gift_date;
            }
        }

        return $cardAttr;
    }

    public function getAllCardTypes() {

        $cardAttr = array();
        $cardData = Mage::helper('Boonagel_Alpesa')->dynoData('alpesa/alpesacard');
        if (count($cardData) < 1) {
            return $cardAttr;
        }
        foreach ($cardData as $cardDatad) {
            $cardStdClass = new stdClass();
            $arrayedCard = explode(",", $cardDatad->card_min_max_points);
            $cardStdClass->minPoints = $arrayedCard[0];
            $cardStdClass->maxPoints = $arrayedCard[1];
            $cardStdClass->cardColor = $cardDatad->card_color;
            $cardStdClass->cardName = $cardDatad->card_name;
            $cardStdClass->cardVoucherAmt = $cardDatad->card_voucher_amount;
            $cardStdClass->cardId = $cardDatad->id;
            $cardStdClass->cardDiscount = $cardDatad->card_discount;
            $cardStdClass->cardGiftDate = $cardDatad->card_gift_date;
            $cardAttr[] = $cardStdClass;
        }

        return $cardAttr;
    }

    public function getInvoices() {
        $alpesapaymenttype = Mage::registry('alpesapaymenttype');
        $arrayedData = array();

        $arrayedData['alpesapaymenttype'] = $alpesapaymenttype;

        if ($alpesapaymenttype == 'voucher') {
            $alpesacardid = Mage::registry('alpesacardid');
            $arrayedData['alpesacardid'] = $alpesacardid;
            //fetch the card
            $arrayedData['alpesacarddata'] = Mage::helper('Boonagel_Alpesa')->dynoData('alpesa/alpesacard', array('id,eq,' . $alpesacardid), 1);
        }

        //$invoiceStatuses =  Mage::getSingleton('sales/order_config')->getVisibleOnFrontStates();
        // new,processing,complete,canceled,closed,holded,payment_review
        $orders = Mage::getResourceModel('sales/order_collection')
                ->addFieldToSelect('*')
                ->addFieldToFilter('customer_id', Mage::getSingleton('customer/session')->getCustomer()->getId())
//                ->addFieldToFilter('status', array('in' => 'new'))
//                ->addFieldToFilter('status','pending')
//                ->addFieldToFilter('total_due',array("gt"=>0.0000))
                ->setOrder('created_at', 'desc')
        ;

         $cleanedorders = array();
//confirm not already logged for payments
        foreach ($orders as $key=>$order) {
            if(Mage::helper('Boonagel_Alpesa')->dynoData('alpesa/alpesainvoice', array('order_id,eq,' . $order->getId()))->count()>0 || Mage::helper('Boonagel_Alpesa')->dynoData('alpesa/alpesavoucher', array('order_id,eq,' . $order->getId()))->count()>0){

            }else{
                $cleanedorders[] = $order;
            }
        }
        $arrayedData['pendingInvoices'] = $cleanedorders;
        return $arrayedData;
    }

    public function getCustomerLogs() {
        $customerId = Mage::getSingleton('customer/session')->getCustomer()->getId();
        $logData = array();
        $logData['walletUsedAmt'] = Mage::helper('Boonagel_Alpesa')->dynoData('alpesa/alpesainvoice', array('user_id,eq,' . $customerId));
        $logData['voucherUsedAmt'] = Mage::helper('Boonagel_Alpesa')->dynoData('alpesa/alpesavoucher', array('user_id,eq,' . $customerId, 'validated,eq,1'));
        return $logData;
    }

    public function alpesaShop() {
        $arrayedData = array();
        $configData = Mage::helper('Boonagel_Alpesa')->getConfigData();

        if (count($configData) === 1) {
            $arrayedData['login'] = Mage::helper('Boonagel_Alpesa')->processFlagPoints($configData, 'login');
            $arrayedData['signup'] = Mage::helper('Boonagel_Alpesa')->processFlagPoints($configData, 'signup');
            $arrayedData['newsletter'] = Mage::helper('Boonagel_Alpesa')->processFlagPoints($configData, 'newsletter');
            $arrayedData['referral'] = Mage::helper('Boonagel_Alpesa')->processFlagPoints($configData, 'referral');
            $currency_point = explode(",", trim($configData->currency_point));
            $arrayedData['point'] = $currency_point[1];
            $arrayedData['amount'] = $currency_point[0];
            $arrayedData['currency'] = Mage::app()->getStore()->getCurrentCurrencyCode();
        }

        return $arrayedData;
    }

    public function referral() {

        $arrayedData = array();
        $customerId = Mage::getSingleton('customer/session')->getCustomer()->getId();

        $latestUrl = null;
        $actualRef = 0;
        $availableRef = 0;

        //get latest url

        $latestUrlData = Mage::helper('Boonagel_Alpesa')->dynoData('alpesa/alpesarefcodes', array('customer_id,eq,' . $customerId), 1, array('id', 'DESC'));
        if ($latestUrlData->count() > 0) {
            $latestUrlDt = $latestUrlData->getFirstItem();
            $code = $latestUrlDt->getCode();
            $latestUrl = Mage::getBaseUrl() . 'alpesa/refer/refer/?code=' . $code;
        }
        $arrayedData['latestUrl'] = $latestUrl;

        //get referral points
        $refPoints = Mage::helper('Boonagel_Alpesa')->dynoData('alpesa/alpesarefpoints', array('customer_id,eq,' . $customerId));

        if ($refPoints->count() > 0) {
            foreach ($refPoints as $refPoint) {
                if ($refPoint->getActual() == 1) {
                    $actualRef += $refPoint->getPoints();
                }
                if ($refPoint->getActual() == 0) {
                    $availableRef += $refPoint->getPoints();
                }
            }
        }
        $arrayedData['actualRef'] = $actualRef;
        $arrayedData['availableRef'] = $availableRef;

        return $arrayedData;
    }

    public function getConditions() {

        $alConditions = Mage::helper('Boonagel_Alpesa')->dynoData('alpesa/alpesacondition');
        return $alConditions;
    }

}
