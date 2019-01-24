<?php

class Boonagel_Alpesa_IndexController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {



        //$this->_sendMessage();

        $this->loadLayout();
        Mage::helper('Boonagel_Alpesa')->setTitle($this,"Alpesa Earn Reedemable Points");
        $this->renderLayout();
    }

    private function _sendMessage() {
        //perform message sending here
        require('AfricasTalkingGateway.php');
        // Be sure to include the file you've just downloaded
        require_once('AfricasTalkingGateway.php');
        // Specify your login credentials
        $username = "jefflilcot";
        $apikey = "6f066e11bcdf444e2bb9e7ffae47f637922e4be246c172702c00b24b4623e4cd";
        // Specify the numbers that you want to send to in a comma-separated list
        // Please ensure you include the country code (+254 for Kenya in this case)
        $recipients = "+254702890401,+254719726698,+254721201431";
        // And of course we want our recipients to know what we really do
        $message = "Alpesa has confirmed that you are this week's winner for a gift voucher worth Ksh 2,300."
                . "Kindly visit alladin.co.ke to shop for free.";
        // Create a new instance of our awesome gateway class
        $gateway = new AfricasTalkingGateway($username, $apikey);
        // Any gateway error will be captured by our custom Exception class below, 
        // so wrap the call in a try-catch block
        try {
            // Thats it, hit send and we'll take care of the rest. 
            $results = $gateway->sendMessage($recipients, $message);

            foreach ($results as $result) {
                // status is either "Success" or "error message"
                echo " Number: " . $result->number;
                echo " Status: " . $result->status;
                echo " MessageId: " . $result->messageId;
                echo " Cost: " . $result->cost . "\n";
            }
        } catch (AfricasTalkingGatewayException $e) {
            echo "Encountered an error while sending: " . $e->getMessage();
        }
        
         die();
    }
    
   

}
