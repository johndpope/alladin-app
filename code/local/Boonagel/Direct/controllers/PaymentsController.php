<?php

class Boonagel_Direct_PaymentsController extends Mage_Core_Controller_Front_Action {

    protected $_orderId;
    protected $configDetails;

    public function payAction() {

        /** check if isset success from 3g gateway */
        $success = filter_input(INPUT_GET, 'success');
        $cancel = filter_input(INPUT_GET, 'cancel');

        $param = Mage::helper('Boonagel_Direct')->configDetails();
        if (!array_key_exists('gateway_url', $param)) {
            Mage::log('Directpayonline config not set.');
            //Redirect to status page with config details not set
            Mage::getSingleton('core/session')->addError('Sorry Config not set.');
            return $this->_redirect('*/*/status');
        }

        if (isset($success) && !empty($success)) {

            $orderId = $success;
            $transactionToken = filter_input(INPUT_GET, 'TransactionToken');
            
            $this->_verifyTokenResponse($transactionToken, $orderId, true);
            Mage::getSingleton('core/session')->addSuccess('Your payment has been received and is being processed.');
            return $this->_redirect('*/*/status');
        }
        /** check if isset cancel from directpayonline gateway * */ elseif (isset($cancel) && !empty($cancel)) {

            $orderId = $cancel;
//            $errorMessage = _('Payment canceled by customer');
            Mage::log('Directpayonline Payment canceled by customer');
            //Redirect to status page with payment canceled
            Mage::getSingleton('core/session')->addError('Payment has been canceled by the customer');
            return $this->_redirect('*/*/status');
        } else {
            /*             * create the directpayonline transaction token* */
            $orderId = Mage::helper('Boonagel_Direct')->lastRealOrderId();

            if (!isset($orderId) || !$orderId) {
                //$message = 'Invalid order ID, please try again later';
                Mage::log("Directpayonline Order Id does not exist.");
                Mage::getSingleton('core/session')->addError('Invalid Order.Kindly contact our customer support for assistance.');
                return $this->_redirect('*/*/status');
            }
            //ensure it is not yet logged ie user trying to resend the same transaction
            $directpayonlinelogs = Mage::helper('Boonagel_Direct')->dynoData('direct/directlog', array('order_id,eq,' . $orderId), 1);
            if ($directpayonlinelogs->count() > 0) {
                Mage::log("Directpayonline User trying to request another transaction token with the same order number.Either they are trying a DOS or hack.");
                Mage::getSingleton('core/session')->addError('Invalid request');
                return $this->_redirect('*/*/status');
            }

            $this->_orderId = $orderId;
            $billingDetails = $this->getBillingDetailsByOrderId($orderId);

            /** Set new directPayCurl object */
            require_once('DirectPayCurl.php');
            $directPayCurl = new DirectPayCurl($billingDetails);
            $response = $directPayCurl->directPaytTokenResult();
            $this->_checkDirectPayResponse($response, $orderId);
        }
    }

    public function getBillingDetailsByOrderId($orderId) {
        /** @var Magento\Sales\Model\Order $order */
        $orderDetails = Mage::helper('Boonagel_Direct')->orderObjectGet($orderId);

        /*         * get billing data* */
        $order_information = Mage::helper('Boonagel_Direct')->salesOrderObject($orderDetails['entity_id']);

        $billingDetails = $order_information->getBillingAddress();
        $ordered_items = $order_information->getAllItems();

        /** New products array */
        $productsArr = [];

        foreach ($ordered_items as $key => $item) {
            /** Product name */
            $productsArr[$key] = $item->getName();
        }

        $param = [
            'order_id' => $orderId,
            'amount' => number_format($order_information->getGrandTotal(), 2, '.', ''),
            'currency' => Mage::helper('Boonagel_Direct')->getCurrentCurrency(),
            'first_name' => $billingDetails->getFirstName(),
            'last_name' => $billingDetails->getLastname(),
            'email' => $billingDetails->getEmail(),
            'phone' => $billingDetails->getTelephone(),
            'address' => $billingDetails->getStreetLine(1),
            'city' => $billingDetails->getCity(),
            'zipcode' => $billingDetails->getPostcode(),
            'country' => $billingDetails->getCountryId(),
            'redirectURL' => Mage::getUrl('directpayonline/payments/pay?success=' . $orderId),
            'backURL' => Mage::getUrl('directpayonline/payments/pay?cancel=' . $orderId),
            'products' => $productsArr
        ];

        return $param;
    }

    /**
     * Check Direct pay response for the first request
     */
    private function _checkDirectPayResponse($response, $orderId) {
        if ($response === FALSE) {

            /*             * unable to connect to the payment gateway log */
//            $errorMessage = _('Payment error: Unable to connect to the payment gateway, please try again later');
            Mage::log('Directpayonline Payment error: Unable to connect to the payment gateway.');
            //redirect to error page for status
            Mage::getSingleton('core/session')->addError('Sorry unable to connect to the payment gateway.Kindly contact our customer support for assistance.');
            return $this->_redirect('*/*/status');
        } else {
            /** manage xml response */
            $this->_getXmlResponse($response, $orderId);
        }
    }

    /**
     * Get and check first xml response
     */
    private function _getXmlResponse($response, $orderId) {
        /** convert the XML result into array */
        $xml = simplexml_load_string($response);


        /** if the result have error ask the customer to try again laterz */
        if ($xml->Result[0] != '000') {
            /**  create error message */
//            $errorMessage = _('Payment error code: ' . $xml->Result[0] . ', ' . $xml->ResultExplanation[0]);
            Mage::log('Directpayonline Payment error code: ' . $xml->Result[0] . ', ' . $xml->ResultExplanation[0]);
            //redirect to error page for status
            Mage::getSingleton('core/session')->addError('Your payment was not successfuly processed.Kindly contact our support center for assistance.');
            return $this->_redirect('*/*/status');
        }

        /** get 3G gateway paymnet URL from config */
        $param = Mage::helper('Boonagel_Direct')->configDetails();
        $transToken = $xml->TransToken[0];
        /*         * save this data to our logs as unverified* */
        $this->_updateDirectPayOnlineStatus($transToken, $orderId);

        /** create url to redirect */
        $paymnetURL = $param['gateway_url'] . "/pay.php?ID=" . $transToken;

        return $this->_redirectUrl($paymnetURL);
    }

    //verify token response
    private function _verifyTokenResponse($transactionToken, $orderId = null, $success = false) {
        if (!isset($transactionToken)) {
//            $errorMessage = _('Transaction token error, please contact support center.');
            Mage::log('Directpayonline Transaction Token error.');
            //redirect to error page for status
//            Mage::getSingleton('core/session')->addError('Transaction token error, kindly contact our support center.');
//            return $this->_redirect('*/*/status');
        }

        /** get verify token response from 3g */
        $response = $this->_verifyToken($transactionToken);
        
        if ($response) {
            if ($response->Result[0] == '000') {
                $comment = 'DirectPayOnline Payment has been processed successfully';
                /*                 * save this data to our logs as complete* */
                $this->_updateDirectPayOnlineStatus($transactionToken, $orderId, true);

                //no need for redirect to success page informing customer that the payment was successful since it will be handled in the background
//                Mage::getSingleton('core/session')->addSuccess('Your payment has been successful.Your product will be shipped soon.For inquries contact customer support.');
//                $this->_redirect('*/*/status');
            } else {

                $errorCode = $response->Result[0];
                $errorDesc = $response->ResultExplanation[0];
                //dblog
//                $errorMessage = _('Payment Failed: ' . $errorCode . ', ' . $errorDesc);
                Mage::log('Directpayonline Payment Failed: ' . $errorCode . ', ' . $errorDesc);
                //no need for redirect to page with error status
//                Mage::getSingleton('core/session')->addError('Payment failded,kindly contact our customer support for assistance.');
//                $this->_redirect('*/*/status');
            }
        }
    }

    /**
     * Verify paymnet token from 3G
     */
    private function _verifyToken($transactionToken) {
        $configDetails = Mage::helper('Boonagel_Direct')->configDetails();

        $inputXml = '<?xml version="1.0" encoding="utf-8"?>
					<API3G>
					  <CompanyToken>' . $configDetails['company_token'] . '</CompanyToken>
					  <Request>verifyToken</Request>
					  <TransactionToken>' . $transactionToken . '</TransactionToken>
					</API3G>';

        $url = $configDetails['gateway_url'] . "/API/v5/";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $inputXml);

        $response = curl_exec($ch);

        curl_close($ch);

        if ($response !== FALSE) {
            /** convert the XML result into array */
            $xml = simplexml_load_string($response);
            return $xml;
        }
        return false;
    }

    //push payment received
    public function pushAction() {
        $pushXml = file_get_contents('php://input');

        if ($pushXml == null) {

            Mage::log('Directpayonline The push notification from 3g is null.');
            return;
        }
        $xml = simplexml_load_string($pushXml);

        /** if the result have error ask the customer to try again laterz */
        if ($xml->Result[0] != '000') {
            //dblog
            Mage::log('Directpayonline Push payment code not 000');
            return;
        }

        //get the data and return an ok then initiate token verify
        $TransactionToken = $xml->TransactionToken[0];
        $this->_returnOkPush();
        //ensure it is not yet already complete to avoid duplication
        $directpayonlinelogs = Mage::helper('Boonagel_Direct')->dynoData('direct/directlog', array('transaction_token,eq,' . $TransactionToken), 1);
        if ($directpayonlinelogs->count() > 0) {
            $directpayonlinelog = $directpayonlinelogs->getFirstItem();
            if ($directpayonlinelog->getStatus != 'complete') {
                $this->_verifyTokenResponse($TransactionToken);
            }
        } else {
            $this->_verifyTokenResponse($TransactionToken);
        }
    }

    private function _returnOkPush() {

        $response = '<?xml version="1.0" encoding="utf-8"?>
					<API3G>
					  <Response>OK</Response>
					</API3G>';

//sending response to the same endpoint.
        header("Content-Type: text/xml; charset=UTF-8");
        echo $response;
    }

    //status info page
    public function statusAction() {
        //load layout
        $this->loadLayout();
        $this->getLayout()->getBlock('root')->setTemplate('page/1column.phtml');
        $block = $this->getLayout()->createBlock('Mage_Core_Block_Template', 'direct', array('template' => 'directpayonline\status.phtml'));
        $this->getLayout()->getBlock('content')->append($block);
        Mage::helper('Boonagel_Direct')->setTitle($this, "DirectPayOnline Payment Logs");
        $this->renderLayout();
    }

    /*     * udpate directpayonline status* */

    private function _updateDirectPayOnlineStatus($transToken, $orderId = null, $success = false) {
        //update our logs and also trigger order status update

       
        if ($orderId == null) {
            
            //verify token successful - payment complete
            //update our logs
            $directpayonlinelogs = Mage::helper('Boonagel_Direct')->dynoData('direct/directlog', array('transaction_token,eq,' . $transToken), 1);
            if ($directpayonlinelogs->count() == 1) {
                $directpayonlinelog = $directpayonlinelogs->getFirstItem();
                $directpayonlinelog->setStatus('complete');
                $directpayonlinelog->save();
            } else {
                $directlog = Mage::getModel('direct/directlog');
                $directlog->getData();
                $directlog->setTransactionToken($transToken);
                $directlog->setStatus('complete');
                $directlog->setUpdatedAt(now());
                $directlog->setcreatedAt(now());
                $directpayonlinelog = $directlog->save();
            }



            //update order status
            $this->_updateOrderStatus($directpayonlinelog->getOrderId(), 'complete');
            //send json data to aramex
            $this->_sendAramex($directpayonlinelog->getOrderId(), 'complete');

            //send email and sms to inform customer payment was successful
            $orderDetails = Mage::helper('Boonagel_Direct')->orderObjectGet($directpayonlinelog->getOrderId());
            $salesOrder = Mage::helper('Boonagel_Cba')->salesOrderObject($orderDetails['entity_id']);
            $data['firstName'] = $orderDetails['customer_firstname'];
            $data['orderIncrementId'] = $directpayonlinelog->getOrderId();
            $data['billingEmail'] = $billingEmail = $salesOrder->getBillingAddress()->getEmail();

            $data['biilingFirstName'] = $salesOrder->getBillingAddress()->getFirstname();
            $this->_sendEmailPaymentComplete($data);
        }

        if ($orderId != null && $success == false) {
            
            //first time therefore need logging this transaction 
            $directlog = Mage::getModel('direct/directlog');
            $directlog->getData();
            $directlog->setTransactionToken($transToken);
            $directlog->setOrderId($orderId);
            $directlog->setUpdatedAt(now());
            $directlog->setcreatedAt(now());
            $dbdata = $directlog->save();

            //update order status
            $this->_updateOrderStatus($orderId, 'unverified');
        }


        if ($orderId != null && $success == true) {
            
            //verify token successful - payment complete success message received
            //update our logs
             $directpayonlinelogs = Mage::helper('Boonagel_Direct')->dynoData('direct/directlog', array('transaction_token,eq,' . $transToken), 1);
            if ($directpayonlinelogs->count() == 1) {
                $directpayonlinelog = $directpayonlinelogs->getFirstItem();
                $directpayonlinelog->setStatus('complete');
                $directpayonlinelog->save();
            } else {
                $directlog = Mage::getModel('direct/directlog');
                $directlog->getData();
                $directlog->setTransactionToken($transToken);
                $directlog->setOrderId($orderId);
                $directlog->setStatus('complete');
                $directlog->setUpdatedAt(now());
                $directlog->setcreatedAt(now());
                $directpayonlinelog = $directlog->save();
            }

            //update order status
            $this->_updateOrderStatus($directpayonlinelog->getOrderId(), 'complete');
            //send json data to aramex
            $this->_sendAramex($directpayonlinelog->getOrderId(), 'complete');

            //send email and sms to inform customer payment was successful
            $orderDetails = Mage::helper('Boonagel_Direct')->orderObjectGet($directpayonlinelog->getOrderId());
            $salesOrder = Mage::helper('Boonagel_Cba')->salesOrderObject($orderDetails['entity_id']);
            $data['firstName'] = $orderDetails['customer_firstname'];
            $data['orderIncrementId'] = $directpayonlinelog->getOrderId();
            $data['billingEmail'] = $billingEmail = $salesOrder->getBillingAddress()->getEmail();

            $data['biilingFirstName'] = $salesOrder->getBillingAddress()->getFirstname();
            $this->_sendEmailPaymentComplete($data);
        }

        //return;
    }

    //send data to aramex
    private function _sendAramex($orderNumber, $transactionStatus) {
        //$transactionStatus = complete
        $orderNum = (string) $orderNumber;
        $configDetails = Mage::helper('Boonagel_Direct')->configDetails();
        $aramexSecret = $configDetails['aramex_secret'];
        /*         * * */
        $hashString = $aramexSecret . $orderNum . $transactionStatus;
        $hashedStuff = strtoupper(hash("sha256", $hashString));
        $hashBase64 = base64_encode($hashedStuff);
        /*         * * */

        $data = array("hashval" => $hashBase64, "status" => $transactionStatus, "orderid" => $orderNum);
        $data_string = json_encode($data);

        $ch = curl_init();


        curl_setopt($ch, CURLOPT_URL, $configDetails['aramex_url']);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);


        $response = curl_exec($ch);

        curl_close($ch);
    }

    //update order status
    private function _updateOrderStatus($orderIncrement, $paymentStatus) {

        $paymentStat = 'directpayonline_payment_unverified';
        $paymentComment = 'Transaction token created but transaction payment not yet validated';

        if ($paymentStatus == 'complete') {

            $paymentStat = 'directpayonline_payment_complete';
            $paymentComment = 'Transaction payment has been validated';
        } else {
            $paymentStat = 'directpayonline_payment_unverified';
        }

        $comment = $paymentComment . ' at ' . now();

        $orders = Mage::getModel('sales/order')->getCollection()->addFieldToFilter('increment_id', array("eq" => $orderIncrement))->setPageSize(1);

        if ($orders->count() == 1) {
            $order = $orders->getFirstItem();
            //set status of the order
            $order->setStatus('DirectPayOnline Payment');
            //set comment of the order
            $order->addStatusToHistory($paymentStat, $comment, false)->setIsVisibleOnFront(1);
            $order->save();
        }
        return;
    }

    /*     * send email to customer with info that payment has been received* */

    private function _sendEmailPaymentComplete($data) {

        //send mpesa instruction through email
        $mailTemplate = Mage::getModel('core/email_template')->loadDefault('direct_payment_info_status');

        $mailTemplateVariables = array();
        $mailTemplateVariables['custFirstName'] = $data['firstName'];
        $mailTemplateVariables['orderIncrementId'] = $data['orderIncrementId'];
        $mailTemplateVariables['logoSource'] = Mage::helper('Boonagel_Direct')->logoSource();

        //get the processed template
        $mailTemplate->getProcessedTemplate($mailTemplateVariables);

        $storeId = Mage::helper('Boonagel_Direct')->storeId();

        $mailTemplate->setSenderEmail(Mage::getStoreConfig('trans_email/ident_general/email', $storeId));

        $mailTemplate->setSenderName(Mage::getStoreConfig('trans_email/ident_general/name', $storeId));


        //send the processed template
        $mailTemplate->send($data['billingEmail'], $data['biilingFirstName'], $mailTemplateVariables);
    }

}
