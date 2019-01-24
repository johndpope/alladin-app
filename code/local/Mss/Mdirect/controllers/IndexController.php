<?php
class Mss_Mdirect_IndexController extends Mage_Core_Controller_Front_Action
{
    protected $_orderId;

    protected $configDetails;

    public function IndexAction()
    {
        $billingDetails = $this->getBillingDetailsByOrderId($this->getRequest()->getParam('order_id'));
        require_once 'DirectPayCurl.php';
        $directPayCurl = new DirectPayCurl($billingDetails);
        $response      = $directPayCurl->directPaytTokenResult();
        $this->_checkDirectPayResponse($response, $this->getRequest()->getParam('order_id'));
    }

    /**
     * Get the Billing details to send in payment gateway
     */
    public function getBillingDetailsByOrderId($orderId)
    {
        /** @var Magento\Sales\Model\Order $order */
        $orderDetails      = Mage::helper('mdirect')->orderObjectGet($orderId);
        $order_information = Mage::helper('mdirect')->salesOrderObject($orderDetails['entity_id']);
        $billingDetails    = $order_information->getBillingAddress();
        $ordered_items     = $order_information->getAllItems();
        $productsArr       = [];

        foreach ($ordered_items as $key => $item) {
            $productsArr[$key] = $item->getName();
        }

        $param = [
            'order_id'    => $orderId,
            'amount'      => number_format($order_information->getGrandTotal(), 2, '.', ''),
            'currency'    => Mage::helper('mdirect')->getCurrentCurrency(),
            'first_name'  => $billingDetails->getFirstName(),
            'last_name'   => $billingDetails->getLastname(),
            'email'       => $billingDetails->getEmail(),
            'phone'       => $billingDetails->getTelephone(),
            'address'     => $billingDetails->getStreetLine(1),
            'city'        => $billingDetails->getCity(),
            'zipcode'     => $billingDetails->getPostcode(),
            'country'     => $billingDetails->getCountryId(),
            'redirectURL' => Mage::getUrl('mdirect/index/pay?success=' . $orderId),
            'backURL'     => Mage::getUrl('mdirect/index/pay?cancel=' . $orderId),
            'products'    => $productsArr,
        ];

        return $param;
    }

    /**
     * Check Direct pay response for the first request
     */
    private function _checkDirectPayResponse($response, $orderId)
    {
        if ($response === false) {
            Mage::log('Directpayonline Payment error: Unable to connect to the payment gateway.');
            Mage::getSingleton('core/session')->addError('Sorry unable to connect to the payment gateway.Kindly contact our customer support for assistance.');
            return $this->_redirect('*/*/status');
        } else {
            $this->_getXmlResponse($response, $orderId);
        }
    }

    /**
     * Get and check first xml response
     */
    private function _getXmlResponse($response, $orderId)
    {
        /** convert the XML result into array */
        $xml = simplexml_load_string($response);

        if ($xml->Result[0] != '000') {
            Mage::log('Directpayonline Payment error code: ' . $xml->Result[0] . ', ' . $xml->ResultExplanation[0]);
            Mage::getSingleton('core/session')->addError('Your payment was not successfuly processed.Kindly contact our support center for assistance.');
            return $this->_redirect('*/*/status');
        }

        /** get 3G gateway paymnet URL from config */
        $param      = Mage::helper('mdirect')->configDetails();
        $transToken = $xml->TransToken[0];
        $paymnetURL = $param['gateway_url'] . "/pay.php?ID=" . $transToken;
        $this->_updateDirectPayOnlineStatus($transToken, $orderId);
        return $this->_redirectUrl($paymnetURL);
    }

    /**
     * closing url
     */
    public function failcloseAction()
    {
        return true;
    }

    /**
     * closing url
     */
    public function successcloseAction()
    {
        return true;
    }

    private function _updateDirectPayOnlineStatus($transToken, $orderId = null, $success = false)
    {
        if ($orderId == null) {
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
            $orderDetails             = Mage::helper('Boonagel_Direct')->orderObjectGet($directpayonlinelog->getOrderId());
            $salesOrder               = Mage::helper('Boonagel_Cba')->salesOrderObject($orderDetails['entity_id']);
            $data['firstName']        = $orderDetails['customer_firstname'];
            $data['orderIncrementId'] = $directpayonlinelog->getOrderId();
            $data['billingEmail']     = $billingEmail     = $salesOrder->getBillingAddress()->getEmail();

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
            $orderDetails             = Mage::helper('Boonagel_Direct')->orderObjectGet($directpayonlinelog->getOrderId());
            $salesOrder               = Mage::helper('Boonagel_Cba')->salesOrderObject($orderDetails['entity_id']);
            $data['firstName']        = $orderDetails['customer_firstname'];
            $data['orderIncrementId'] = $directpayonlinelog->getOrderId();
            $data['billingEmail']     = $billingEmail     = $salesOrder->getBillingAddress()->getEmail();

            $data['biilingFirstName'] = $salesOrder->getBillingAddress()->getFirstname();
            $this->_sendEmailPaymentComplete($data);
        }

        //return;
    }

    //update order status
    private function _updateOrderStatus($orderIncrement, $paymentStatus)
    {

        $paymentStat    = 'directpayonline_payment_unverified';
        $paymentComment = 'Transaction token created but transaction payment not yet validated';

        if ($paymentStatus == 'complete') {

            $paymentStat    = 'directpayonline_payment_complete';
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

    //send data to aramex
    private function _sendAramex($orderNumber, $transactionStatus)
    {
        $orderNum      = (string) $orderNumber;
        $configDetails = Mage::helper('mdirect')->configDetails();
        $aramexSecret  = $configDetails['aramex_secret'];
        $hashString    = $aramexSecret . $orderNum . $transactionStatus;
        $hashedStuff   = strtoupper(hash("sha256", $hashString));
        $hashBase64    = base64_encode($hashedStuff);

        $data        = array("hashval" => $hashBase64, "status" => $transactionStatus, "orderid" => $orderNum);
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

    private function _sendEmailPaymentComplete($data)
    {
        $mailTemplate                              = Mage::getModel('core/email_template')->loadDefault('direct_payment_info_status');
        $mailTemplateVariables                     = array();
        $mailTemplateVariables['custFirstName']    = $data['firstName'];
        $mailTemplateVariables['orderIncrementId'] = $data['orderIncrementId'];
        $mailTemplateVariables['logoSource']       = Mage::helper('mdirect')->logoSource();
        $mailTemplate->getProcessedTemplate($mailTemplateVariables);
        $storeId = Mage::helper('mdirect')->storeId();
        $mailTemplate->setSenderEmail(Mage::getStoreConfig('trans_email/ident_general/email', $storeId));
        $mailTemplate->setSenderName(Mage::getStoreConfig('trans_email/ident_general/name', $storeId));
        $mailTemplate->send($data['billingEmail'], $data['biilingFirstName'], $mailTemplateVariables);
    }

    public function payAction()
    {
        $success = filter_input(INPUT_GET, 'success');
        $cancel  = filter_input(INPUT_GET, 'cancel');

        $param = Mage::helper('mdirect')->configDetails();
        if (!array_key_exists('gateway_url', $param)) {
            Mage::log('Directpayonline config not set.');
            return $this->_redirectUrl(Mage::getBaseUrl() . "mdirect/index/failclose");
        }

        if (isset($success) && !empty($success)) {

            $orderId          = $success;
            $transactionToken = filter_input(INPUT_GET, 'TransactionToken');

            $this->_verifyTokenResponse($transactionToken, $orderId, true);
            return $this->_redirectUrl(Mage::getBaseUrl() . "mdirect/index/successclose");
        } elseif (isset($cancel) && !empty($cancel)) {
            $orderId = $cancel;
            Mage::log('Directpayonline Payment canceled by customer');
            return $this->_redirectUrl(Mage::getBaseUrl() . "mdirect/index/failclose");
        } else {
            $orderId = Mage::helper('mdirect')->lastRealOrderId();

            if (!isset($orderId) || !$orderId) {
                Mage::log("Directpayonline Order Id does not exist.");
                return $this->_redirectUrl(Mage::getBaseUrl() . "mdirect/index/failclose");
            }
            //ensure it is not yet logged ie user trying to resend the same transaction
            $directpayonlinelogs = Mage::helper('Boonagel_Direct')->dynoData('direct/directlog', array('order_id,eq,' . $orderId), 1);
            if ($directpayonlinelogs->count() > 0) {
                Mage::log("Directpayonline User trying to request another transaction token with the same order number.Either they are trying a DOS or hack.");
                return $this->_redirectUrl(Mage::getBaseUrl() . "mdirect/index/failclose");
            }

            $this->_orderId = $orderId;
            $billingDetails = $this->getBillingDetailsByOrderId($orderId);

            /** Set new directPayCurl object */
            require_once 'DirectPayCurl.php';
            $directPayCurl = new DirectPayCurl($billingDetails);
            $response      = $directPayCurl->directPaytTokenResult();
            $this->_checkDirectPayResponse($response, $orderId);
        }
    }

    //verify token response
    private function _verifyTokenResponse($transactionToken, $orderId = null, $success = false)
    {
        if (!isset($transactionToken)) {
            Mage::log('Directpayonline Transaction Token error.');
        }
        $response = $this->_verifyToken($transactionToken);

        if ($response) {
            if ($response->Result[0] == '000') {
                $comment = 'DirectPayOnline Payment has been processed successfully';
                $this->_updateDirectPayOnlineStatus($transactionToken, $orderId, true);
            } else {
                $errorCode = $response->Result[0];
                $errorDesc = $response->ResultExplanation[0];
                Mage::log('Directpayonline Payment Failed: ' . $errorCode . ', ' . $errorDesc);
            }
        }
    }

    /**
     * Verify paymnet token from 3G
     */
    private function _verifyToken($transactionToken)
    {
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

        if ($response !== false) {
            /** convert the XML result into array */
            $xml = simplexml_load_string($response);
            return $xml;
        }
        return false;
    }
}
