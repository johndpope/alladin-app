<?php

class Boonagel_Cba_Model_Observer {

    
    //admin logs in sales view page
    public function cbaAdminLogs(Varien_Event_Observer $observer){
        $block = $observer->getBlock();
        if(($block->getNameInLayout() == 'order_info') && ($child = $block->getChild('cbaorderlog'))){
            $transport = $observer->getTransport();
            if($transport){
                $html = $transport->getHtml();
                $html .= $child->toHtml();
                $transport->setHtml($html);
            }
        }
        
    }

 public function orderPlaced(Varien_Event_Observer $observer){
         $order = $observer->getEvent()->getOrder();
         $orderIncrementId = $order->getIncrementId();
         $payment_title =  $order->getPayment()->getMethodInstance()->getTitle();
         $payment_title_stripped = str_replace(" ", "_", strtolower($payment_title));
         
         if( $payment_title_stripped === "lipa_na_mpesa"){
             //send sms with instructions
             $this->_sendSms($orderIncrementId);
         }
         
     }
    private function _sendSms($orderIncrementId) {

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
                . "1. Go to safaricom menu and select Mpesa option.\n"
                . "2. Select Lipa na Mpesa.\n"
                . "3. Select Pay Bill.\n"
                . "4. Enter business number as : " . Mage::helper('Boonagel_Cba')->payBill() . "\n"
                . "5. Enter account number as :  $orderIncrementId" . "\n"
                . "6. Enter amount : " . Mage::helper('Boonagel_Cba')->getCurrentCurrency() . ' ' . Mage::helper('Boonagel_Cba')->formatNumber($orderDetails['grand_total'], 2) . "\n"
                . "7. Enter your Mpesa pin.\n"
                . "8. Confirm and press Ok to complete payment.\n";
        $receipt = $billingAddress->getTelephone();

        //determine it is  a kenyan number ie get the last 9 digits
        if ($receipt != null) {
            $recipients = "+254" . substr($receipt, -9);
            $this->_sendSmsAfricasTalking($smsMessage, $recipients);
        }

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

}
