<?php

class Boonagel_Cba_PaymentsController extends Mage_Core_Controller_Front_Action {

    //C:\xampp\htdocs\OneDrive\alladin\app\locale\en_US\template\email\cba_mpesa_*
    public function indexAction() {



        //$this->_sendMessage();

        $this->loadLayout();
        Mage::helper('Boonagel_Cba')->setTitle($this, "Cba Payments");
        $this->renderLayout();
    }

    public function redirectAction() {


        //send sms and email
        //$this->_sendSmsEmail();

        //load layout
        $this->loadLayout();
        $this->getLayout()->getBlock('root')->setTemplate('page/1column.phtml');
        $block = $this->getLayout()->createBlock('Mage_Core_Block_Template', 'cba', array('template' => 'cba/paynow.phtml'));
        $this->getLayout()->getBlock('content')->append($block);
        $this->renderLayout();
    }

    public function alcbadAction() {
       /** $cbaXml = file_get_contents('php://input');
        echo $cbaXml;
        Mage::log($cbaXml);**/
    }

    public function alcbaAction() {
        $cbaXml = file_get_contents('php://input');


        //get config data
        $cbaConfigData = Mage::helper('Boonagel_Cba')->getConfigData();

        //response gateway
        if (isset($_SERVER['HTTP_REFERER'])) {
            $getResponseGateway = $_SERVER['HTTP_REFERER'];
        } else {
            $getResponseGateway = "http://localhost";
        }



        if ($cbaConfigData == null) {
            echo 'Configuration data is null';
            Mage::log('Configuration data is null:not yet set');
            //send response to cba
            $this->_returnResponseString(false, $getResponseGateway);
            return;
        }

        /*         * From database* */
        $secretKey = $cbaConfigData->getSecret(); //from db
		//$secretKey = 'alladin';
        if ($cbaConfigData->getResponseGateway() != null) {
            $getResponseGateway = $cbaConfigData->getResponseGateway();
        }


        if ($cbaXml == null) {
            echo 'The push notification from cba is null.';
            Mage::log('The push notification from cba is null.');
            //send response to cba
            $this->_returnResponseString(false, $getResponseGateway);
            return;
        }

        $cleanXml = str_ireplace(['soapenv:', 'SOAP'], '', $cbaXml);
        //confirm no errors
        $xmlData = simplexml_load_string($cleanXml);

        $HashVal = $xmlData->Body->CBAPaymentNotificationRequest->HashVal;

        $User = $xmlData->Body->CBAPaymentNotificationRequest->User; //from db
        $Password = $xmlData->Body->CBAPaymentNotificationRequest->Password; //from db
        $TransType = $xmlData->Body->CBAPaymentNotificationRequest->TransType;
        $TransID = $xmlData->Body->CBAPaymentNotificationRequest->TransID;
        $TransTime = $xmlData->Body->CBAPaymentNotificationRequest->TransTime;
        $TransAmount = $xmlData->Body->CBAPaymentNotificationRequest->TransAmount;
        $AccountNr = $xmlData->Body->CBAPaymentNotificationRequest->AccountNr;
        $Narrative = $xmlData->Body->CBAPaymentNotificationRequest->Narrative;  /*         * its the order number* */
        $PhoneNr = $xmlData->Body->CBAPaymentNotificationRequest->PhoneNr;
        $CustomerName = $xmlData->Body->CBAPaymentNotificationRequest->CustomerName;
        $Status = $xmlData->Body->CBAPaymentNotificationRequest->Status;

        //$hashString = 'SecretKey+TransType+TransID+TransTime+TransAmount+AccountNr+Narrative+PhoneNr+CustomerName+Status';
        //$hashString = 'SecretKeyTransTypeTransIDTransTimeTransAmountAccountNrNarrativePhoneNrCustomerNameStatus';
        //use the hashaval to determine if its a genuine push from cba
        $hashString = $secretKey . $TransType . $TransID . $TransTime . $TransAmount . $AccountNr . $Narrative . $PhoneNr . $CustomerName . $Status;
        //$hashString = "jefflilcotPay BillJK2NLJVA1820151102200912100.008801006930300017254725629786ALEXANDER KAHIGASUCCESS";
        $hashedStuff = strtoupper(hash("sha256", $hashString));
        //echo $hashedStuff;
        $cbaHashBase64 = base64_encode($hashedStuff);

//          //return;
        //echo $cbaHashBase64;return;
        if ($cbaHashBase64 != $HashVal) {
            //The response has been compromised
            //send response to cba
            //echo 'The response has been compromised';
            Mage::log('Cba Mpesa response has been compromised');
            $this->_returnResponseString(false, $getResponseGateway);
            return;
        }


        //send response to cba
        $this->_returnResponseString(true, $getResponseGateway);


        //save to db the logged payment
        $cbaMpesaLog = Mage::getModel('cba/cbalog');
        $cbaMpesaLog->getData();
        $cbaMpesaLog->setHashValue($HashVal);
        $cbaMpesaLog->setTransType($TransType);
        $cbaMpesaLog->setTransId($TransID);
        $cbaMpesaLog->setTransTime($TransTime);
        $cbaMpesaLog->setTransAmount($TransAmount);
        $cbaMpesaLog->setAccountNr($AccountNr);
        $cbaMpesaLog->setNarrative($Narrative);
        $cbaMpesaLog->setPhoneNr($PhoneNr);
        $cbaMpesaLog->setCustomerName($CustomerName);
        $cbaMpesaLog->setStatus($Status);
        $cbaMpesaLog->setOrderId($Narrative);
        $cbaMpesaLog->setResponse(1);
        $cbaMpesaLog->setUpdatedAt(now());
        $cbaMpesaLog->setcreatedAt(now());
        $dbdata = $cbaMpesaLog->save();

        //get the order
        $orders = Mage::getModel('sales/order')->getCollection()->addFieldToFilter('increment_id', array("eq" => $Narrative))->setPageSize(1);

        if ($orders->count() != 1) {

            //save this erronous order id
            $dbdata->setErronous(1);
            $dbdata->save();

            Mage::log("CBA PAYMENTS:The order could not be found or many orders of the same increment id selected.");
            //send message
            $this->_sendMessage($dbdata, false);
            return;
        }

        $order = $orders->getFirstItem();

        $grandTotal = $order->getGrandTotal();


        //get all the logs for this specific order
        $mpesaPaymentLogs = Mage::helper('Boonagel_Cba')->dynoData('cba/cbalog', array('order_id,eq,' . $Narrative));
        $cbaTotal = 0;
        foreach ($mpesaPaymentLogs as $mpesaPaymentLog) {
            $cbaTotal += $mpesaPaymentLog->getTransAmount();
        }

        if ($cbaTotal >= $grandTotal) {
            //send message
            $this->_sendMessage($dbdata, true, $Narrative, $grandTotal, $cbaTotal, "complete", $order);
            //update order and change status to "paid through cba",add order comment(totalAmt,phoneNumber that paid,customer name that paid)
            $this->_updateOrderStatus($Narrative, $dbdata, true, $cbaTotal);
        } else {
            //send message
            $this->_sendMessage($dbdata, true, $Narrative, $grandTotal, $cbaTotal, "pending", $order);

            $this->_updateOrderStatus($Narrative, $dbdata, false, $cbaTotal);
        }
    }

    private function _returnResponseString($status, $ResponseGateway = null) {

        
        $cbaStatus = 'FAIL';
        if ($status == true) {
            $cbaStatus = 'OK';
        }

        $response = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
<soapenv:Header/>
<soapenv:Body>
<CBAPaymentNotificationResult><Result>'
                . $cbaStatus . '</Result></CBAPaymentNotificationResult>
</soapenv:Body>
</soapenv:Envelope>';

//since sending response to the same endpoint.
       header("Content-Type: text/xml; charset=UTF-8");
      echo $response;
        
        
        
        /*         * $ch = curl_init();

          curl_setopt($ch, CURLOPT_URL, $ResponseGateway);
          curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
          curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
          curl_setopt($ch, CURLOPT_POSTFIELDS, $response);

          curl_exec($ch);

          curl_close($ch);* */


        /**  $anHttp = array(
          'http' =>
          array(
          'method' => 'POST',
          'header' => 'Content-Type:text/xml',
          'content' => $response
          )
          );

          $context = stream_context_create($anHttp);
          $contents = file_get_contents($outboundUrl, false, $context);

          echo $contents;

          return;  * */
    }

    //used for payments made
    private function _sendMessage($latestData, $orderEXists = true, $orderIncrement = 0, $grandTotal = 0, $cbaTotal = 0, $transactionStatus = "complete", $order = null) {

        if ($orderEXists == false) {
            //order number that customer paid does not exist.
            //no aramex,no email(since order number is unknown).
            $customerData = Mage::helper('Boonagel_Cba')->getCustomerContacts();
            $message = "Hello " . $latestData->getCustomerName() . ",\n"
                    . "kindly contact Alladin's customer care support at this number " . $customerData[0] . " or email us at " . $customerData[1] . " because the account number you entered is incorrect so as to initiate manual processing of your payment.";
            //$this->_sendSmsAfricasTalking($message, '+' . $latestData->getPhoneNr());
            return;
        }

        if ($orderEXists == true) {

            //send sms
            $this->_sendSms($orderIncrement, $transactionStatus, $grandTotal, $cbaTotal, $latestData);
            //send email
            $this->_sendEmail($orderIncrement, $transactionStatus, $grandTotal, $cbaTotal, $latestData);
            //send aramex
            $this->_sendAramex($orderIncrement, $transactionStatus, $latestData);
        }
    }

    //used for payments made
    private function _sendSms($incrementId, $orderPaymentStatus, $grandTotal, $paidTotal, $latestData) {
        //send this message to the customer that paid the latest to their phone number
        //not yet implemented -for checkout
        $customerPhoneNumber = $latestData->getPhoneNr();

        $receivedAmt = (int) $latestData->getTransAmount();

        $receivedAmt = Mage::helper('Boonagel_Cba')->getCurrentCurrency() . ' ' . Mage::helper('Boonagel_Cba')->formatNumber($receivedAmt, 2);
        $customerName = $latestData->getCustomerName();
        $thisTotalPaid = Mage::helper('Boonagel_Cba')->getCurrentCurrency() . ' ' . Mage::helper('Boonagel_Cba')->formatNumber($paidTotal, 2);
        $thisGrandTotal = Mage::helper('Boonagel_Cba')->getCurrentCurrency() . ' ' . Mage::helper('Boonagel_Cba')->formatNumber($grandTotal, 2);

        $orderRemainderAmt = $grandTotal - $paidTotal;
        $remainingAmt = Mage::helper('Boonagel_Cba')->getCurrentCurrency() . '0.00';
        if ($orderRemainderAmt > 0) {
            //return positive they paid exact or they have a pending payment amount.
            $remainingAmt = Mage::helper('Boonagel_Cba')->getCurrentCurrency() . ' -' . Mage::helper('Boonagel_Cba')->formatNumber($orderRemainderAmt, 2);
        } else {
            //return - meaning they paid excess.
            $orderRemainderAmt = $orderRemainderAmt * (-1);
            $remainingAmt = Mage::helper('Boonagel_Cba')->getCurrentCurrency() . ' +' . Mage::helper('Boonagel_Cba')->formatNumber($orderRemainderAmt, 2);
        }

        $message = "Hello " . $customerName . ".Alladin has received your payment amounting to " . $receivedAmt . " for order " . $incrementId . "."
                . "Order Grand Total:" . $thisGrandTotal . ";Total Paid Upto Now:" . $thisTotalPaid . ";Remaining amount:" . $remainingAmt;

        //$this->_sendSmsAfricasTalking($message, '+' . $customerPhoneNumber);
    }

    //used for payments made
    private function _sendEmail($incrementId, $orderPaymentStatus, $grandTotal, $paidTotal, $latestData) {
        //send this message to the customer's email
        //get customer details from the order ie the customer's email address
        $orderDetails = Mage::helper('Boonagel_Cba')->orderObjectGet($incrementId);

        if ($orderDetails == null) {
            return;
        }

        /*         * get billing data* */
        $salesOrder = Mage::helper('Boonagel_Cba')->salesOrderObject($orderDetails['entity_id']);

        if ($salesOrder == null) {
            return;
        }
        $billingEmail = $salesOrder->getBillingAddress()->getEmail();

        if ($billingEmail == null) {
            return;
        }
        $billingFirstName = $salesOrder->getBillingAddress()->getFirstname();
        /*         * * */

        $mailTemplate = Mage::getModel('core/email_template')->loadDefault('cba_mpesa_payment_info');

        $mailTemplateVariables = array();

        $mailTemplateVariables['custFirstName'] = $orderDetails['customer_firstname'];

        if ($orderPaymentStatus == 'complete') {
            $mailTemplateVariables['orderStatus'] = 'PAYMENT COMPLETE';
        } else {
            $mailTemplateVariables['orderStatus'] = 'PAYMENT PENDING';
        }

        $receivedAmt = (int) $latestData->getTransAmount();
        $mailTemplateVariables['orderIncrementId'] = $incrementId;
        $mailTemplateVariables['grandTotal'] = Mage::helper('Boonagel_Cba')->getCurrentCurrency() . ' ' . Mage::helper('Boonagel_Cba')->formatNumber($grandTotal, 2);
        $mailTemplateVariables['latestAmount'] = Mage::helper('Boonagel_Cba')->getCurrentCurrency() . ' ' . Mage::helper('Boonagel_Cba')->formatNumber($receivedAmt, 2);
        $mailTemplateVariables['orderTotalPaid'] = Mage::helper('Boonagel_Cba')->getCurrentCurrency() . ' ' . Mage::helper('Boonagel_Cba')->formatNumber($paidTotal, 2);

        $orderRemainderAmt = $grandTotal - $paidTotal;
        if ($orderRemainderAmt >= 0) {
            //return positive they paid exact or they have a pending payment amount.
            $mailTemplateVariables['orderRemainder'] = Mage::helper('Boonagel_Cba')->getCurrentCurrency() . ' ' . Mage::helper('Boonagel_Cba')->formatNumber($orderRemainderAmt, 2);
        } else {
            //return - meaning they paid excess.
            $orderRemainderAmt = $orderRemainderAmt * (-1);
            $mailTemplateVariables['orderRemainder'] = 'Over paid By ' . Mage::helper('Boonagel_Cba')->getCurrentCurrency() . ' ' . Mage::helper('Boonagel_Cba')->formatNumber($orderRemainderAmt, 2);
        }

        $mailTemplateVariables['logoSource'] = Mage::helper('Boonagel_Cba')->logoSource();

        //get the processed template
        $mailTemplate->getProcessedTemplate($mailTemplateVariables);

        $storeId = Mage::helper('Boonagel_Cba')->storeId();

        $mailTemplate->setSenderEmail(Mage::getStoreConfig('trans_email/ident_general/email', $storeId));

        $mailTemplate->setSenderName(Mage::getStoreConfig('trans_email/ident_general/name', $storeId));

        //send the processed template
        $mailTemplate->send($billingEmail, $billingFirstName, $mailTemplateVariables);
    }

    //used for payments made
    private function _sendAramex($orderNumber, $transactionStatus, $latestData) {
        //$transaction either complete,pending.
        //phone number of customer that paid.
        //through json
         $aramexData = Mage::helper('Boonagel_Cba')->aramexGateway();
         
        $amount = (string)$latestData->getTransAmount();
        $orderNum = (string)$orderNumber;
        $aramexSecret = $aramexData['secret'];
        /****/
        $hashString = $aramexSecret.$amount.$orderNum.$transactionStatus;
        $hashedStuff = strtoupper(hash("sha256", $hashString));
        $hashBase64 = base64_encode($hashedStuff);
        /****/
        
        $data = array("hashval"=>$hashBase64,"status"=>$transactionStatus,"order" => $orderNum, "amount" => $amount);
        $data_string = json_encode($data);
        
        $ch = curl_init();

       
        curl_setopt($ch, CURLOPT_URL, $aramexData['url']);
          curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
          curl_setopt($ch, CURLOPT_HTTPHEADER,array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)));
          curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        
        
        $result = curl_exec($ch);

        curl_close($ch);
    }

    public function aramexAction() {
        
        /**header('Content-type: application/json');
        $json = file_get_contents('php://input');
        echo $json;
$obj = json_decode($json);
echo var_dump($obj);die();**/
        
    }

    //update order status
    private function _updateOrderStatus($orderIncrement, $latestData, $paymentStatus, $cbaTotal) {
        //use the latest data to append cba log
        //get all cba log data
        //use the order increment to update status & comment of the order
        //$paymentStatus=true(fully paid),=false(pending)
        $paymentStat = 'COMPLETE';
        if ($paymentStatus == false) {
            $paymentStat = 'cba_payment_pending';
        } else {
            $paymentStat = 'cba_payment_complete';
        }

        $timestamp = strtotime($latestData->getTransTime());
        $paymentDate = date('d-m-Y h:i:s', $timestamp);
        $comment = ' Amount : ' . $latestData->getTransAmount() .
                ', Paid on : ' . $paymentDate
                . ', By : ' . $latestData->getCustomerName()
                . ', Through : +' . $latestData->getPhoneNr()
                . ', Current Total Paid : ' . Mage::helper('Boonagel_Cba')->formatNumber($cbaTotal, 2);

        $orders = Mage::getModel('sales/order')->getCollection()->addFieldToFilter('increment_id', array("eq" => $orderIncrement))->setPageSize(1);

        if ($orders->count() == 1) {
            $order = $orders->getFirstItem();
            //set status of the order
            $order->setStatus('Cba Payment');
            //set comment of the order
            $order->addStatusToHistory($paymentStat, $comment, false)->setIsVisibleOnFront(1);
            $order->save();
        }
        return;
    }

    //send sms and email on successful order placed
 private function _sendSmsEmail() {

        $orderIncrementId = Mage::getSingleton('checkout/session')->getLastRealOrderId();
       $orderPlaced = Mage::helper('Boonagel_Cba')->orderObjectGetNew($orderIncrementId);
        $orderDetails = $orderPlaced->getData();

        if ($orderDetails == null) {
            return;
        }

        /**$lastOrder = Mage::getModel('sales/order')->load(Mage::getSingleton('checkout/session')->getLastOrderId());
        $billingAddress = $lastOrder->getBillingAddress();**/
        
        $billingAddress = $orderPlaced->getBillingAddress();

        //Thank you steve,your order number is 10000123,kindly pay through M-pesa by
        $smsMessage = "Thank you " . $orderDetails['customer_firstname'] . " for shopping at Alladin.\n"
                . "1.Go to safaricom menu and select Mpesa option.\n"
                . "2.Select Lipa na Mpesa.\n"
                . "3.Select Pay Bill.\n"
                . "4.Enter business number as : " . Mage::helper('Boonagel_Cba')->payBill() . "\n"
                . "5.Enter account number as :  $orderIncrementId" . "\n"
                . "6.Enter amount : " . Mage::helper('Boonagel_Cba')->getCurrentCurrency() . ' ' . Mage::helper('Boonagel_Cba')->formatNumber($orderDetails['grand_total'], 2) . "\n"
                . "7.Enter your Mpesa pin.\n"
                . "8.Confirm and press Ok to complete payment.\n";
        $receipt = $billingAddress->getTelephone();

        //determine it is  a kenyan number ie get the last 9 digits
        if ($receipt != null) {
            $recipients = "+254" . substr($receipt, -9);
            $this->_sendSmsAfricasTalking($smsMessage, $recipients);
        }

        /*         * billing data* */
        /**$billingEmail = $billingAddress->getEmail();

        if ($billingEmail == null) {
            return;
        }
        $billingFirstName = $billingAddress->getFirstname();
        /*         * * */

        /**$this->_sendEmailMpesaInstructions(
                array('firstName' => $orderDetails['customer_firstname'],
                    'grandTotal' => Mage::helper('Boonagel_Cba')->formatNumber($orderDetails['grand_total'], 2),
                    'orderIncrementId' => $orderIncrementId,
                    'billingEmail' => $billingEmail,
                    'biilingFirstName' => $billingFirstName)
        );**/
    }

    private function _sendSmsAfricasTalking($smsMessage, $recipients) {
        // Be sure to include the file you've just downloaded
        require_once('AfricasTalkingGateway.php');
        // Specify your login credentials
        $username = "ALLADIN_FASHION";
        $apikey = "a053d703d247c1977bca19867ccb0e4cfc9f6cc089f635187f860024107e7dba";
        $from = "ALLADIN";
        // Specify the numbers that you want to send to in a comma-separated list
        // Please ensure you include the country code (+254 for Kenya in this case)
        // Create a new instance of our awesome gateway class
        $gateway = new AfricasTalkingGateway($username, $apikey);
        // Any gateway error will be captured by our custom Exception class below, 
        // so wrap the call in a try-catch block
        try {
            // Thats it, hit send and we'll take care of the rest. 
            $results = $gateway->sendMessage($recipients, $smsMessage,$from);

            /** foreach ($results as $result) {
              // status is either "Success" or "error message"
              echo " Number: " . $result->number;
              echo " Status: " . $result->status;
              echo " MessageId: " . $result->messageId;
              echo " Cost: " . $result->cost . "\n";
              }* */
        } catch (AfricasTalkingGatewayException $e) {
            Mage::log("Encountered an error while sending: " . $e->getMessage());
        }
    }
    private function _sendEmailMpesaInstructions($data) {
        //send mpesa instruction through email
        $mailTemplate = Mage::getModel('core/email_template')->loadDefault('cba_mpesa_instructions');

        $mailTemplateVariables = array();
        $mailTemplateVariables['custFirstName'] = $data['firstName'];
        $mailTemplateVariables['payBill'] = Mage::helper('Boonagel_Cba')->payBill();
        $mailTemplateVariables['orderIncrementId'] = $data['orderIncrementId'];
        $mailTemplateVariables['currencyCode'] = Mage::helper('Boonagel_Cba')->getCurrentCurrency();
        $mailTemplateVariables['grandTotal'] = $data['grandTotal'];
        $mailTemplateVariables['logoSource'] = Mage::helper('Boonagel_Cba')->logoSource();

        //get the processed template
        $mailTemplate->getProcessedTemplate($mailTemplateVariables);

        $storeId = Mage::helper('Boonagel_Cba')->storeId();

        $mailTemplate->setSenderEmail(Mage::getStoreConfig('trans_email/ident_general/email', $storeId));

        $mailTemplate->setSenderName(Mage::getStoreConfig('trans_email/ident_general/name', $storeId));


        //send the processed template
        $mailTemplate->send($data['billingEmail'], $data['biilingFirstName'], $mailTemplateVariables);
    }

}
