<?php

class Boonagel_Alpesa_Model_Observer {

    public function logLoginEvent($observer) {
        //Zend_Debug::dump($observer,"User Logging data");
        $customerId = $observer["customer"]["entity_id"];

        //get the configuration data and use to determine several factors ie 
        //if login is enabled,calculate the next projected interval
        $configData = Mage::helper('Boonagel_Alpesa')->getConfigData();
        if (count($configData) == 1) {


            $processedFlag = Mage::helper('Boonagel_Alpesa')->processFlag($configData, 'login');

            if ($processedFlag == true) {

                $flagAwardPoints = false;

                $currentTimestamp = now();

                //get this users' last logged in data from model
                $alpesauser = Mage::getModel('alpesa/alpesauser')->getCollection()
                                ->addFieldToFilter('user_id', array('eq' => $customerId))
                                ->addFieldToFilter('log_type', array('eq' => 'logging-session'))
                                ->setOrder('id', 'DESC')->setPageSize(1);

                $currentUserCount = $alpesauser->count();

                if ($currentUserCount == 1) {
                    //use the current timestamp to compare if it is larger than the next projected login time interval
                    $userRecentLog = $alpesauser->getLastItem();
                    $nextRecentLogin = $userRecentLog->next_login;
                    if (strtotime($currentTimestamp) > strtotime($nextRecentLogin)) {
                        $flagAwardPoints = true;
                    }
                } else {
                    $flagAwardPoints = true;
                }

                if ($flagAwardPoints == true) {

                    $updatedAt = now();
                    $createdAt = now();

                    //award points accordingly and update model alpesapoints
                    $points = Mage::helper('Boonagel_Alpesa')->processFlagPoints($configData, 'login');
                    $amount = Mage::helper('Boonagel_Alpesa')->pointPriceConversion($configData, $points, '', 'pnt-curr');
                    $dbpointsdata = Mage::helper('Boonagel_Alpesa')->savePointsToPointsModel($customerId, $amount, $points, 1);

                    //log the next projected login time interval update alpesauser model
                    $current_login = $currentTimestamp;
                    $next_login = $this->nextProjectedTime($current_login, $configData['login_interval']);

                    $alpesauser = Mage::getModel('alpesa/alpesauser');
                    $alpesauser->getData();
                    $alpesauser->setUserId($customerId);
                    $alpesauser->setLogType('logging-session');
                    $alpesauser->setCurrentLogin($current_login);
                    $alpesauser->setNextLogin($next_login);
                    $alpesauser->setUpdatedAt($updatedAt);
                    $alpesauser->setcreatedAt($createdAt);
                    $dbuserdata = $alpesauser->save();

                    //trigger an event to process points conditional target
                    Mage::helper('Boonagel_Alpesa')->triggerEventPointsUpdated($customerId);

                    //trigger an event to process actual points
                    Mage::helper('Boonagel_Alpesa')->triggerUpdateActualPoints($customerId, $dbpointsdata->getId());
                }
            }
        }
    }

    /*     * calculate the next projected login time* */

    private function nextProjectedTime($currentTime, $interval) {
        //$interval = '24,h';
        $intevalArray = explode(",", trim($interval));

        $date = new DateTime($currentTime);

        if (count($intevalArray) > 1) {
            $timeInterval = $intevalArray[0];
            $timeFormat = $intevalArray[1];

            if ($timeFormat == 'h') {
                $dateInterval = 'PT' . $timeInterval . 'H';
            }
            if ($timeFormat == 'i') {
                $dateInterval = 'PT' . $timeInterval . 'M';
            }
            if ($timeFormat == 's') {
                $dateInterval = 'PT' . $timeInterval . 'S';
            }


            $date->add(new DateInterval($dateInterval));
        }


        return $date->format('Y-m-d H:i:s');
    }

    /*     * success signup event* */

    public function succesSignupEvent($observer) {

        $customer = $observer->getCustomer();

        //ensure customer is logged in before further processing

        if ($customer != null) {
            $customerId = $customer->getId();
            $configData = Mage::helper('Boonagel_Alpesa')->getConfigData();
            if (count($configData) == 1) {

                $processedFlag = Mage::helper('Boonagel_Alpesa')->processFlag($configData, 'signup');

                if ($processedFlag == true) {
                    //process the sign up and award points accordingly
                    $this->_saveEventToModelTriggerEvents($configData, 'signup', $customerId);
                }
            }
        }
        //get the configuration data and use to determine several factors ie 
        //if login is enabled,calculate the next projected interval
    }

    /*     * successfuly subscribed to the newsletter* */

    public function newsletterSubscribed($observer) {

        $configData = Mage::helper('Boonagel_Alpesa')->getConfigData();
        if (count($configData) == 1) {

            $customer = Mage::getSingleton('customer/session')->getCustomer();


            //ensure customer is logged in before further processing
            if ($customer != null) {

                $customerId = $customer->getId();

                $processedFlag = Mage::helper('Boonagel_Alpesa')->processFlag($configData, 'newsletter');

                if ($processedFlag == true) {
                    //process newsletter signup evento

                    $event = $observer->getEvent();
                    $subscriber = $event->getDataObject();
                    $data = $subscriber->getData();
                    //$email = $data['subscriber_email'];


                    $statusChange = $subscriber->getIsStatusChanged();
                    if ($data['subscriber_status'] == "1" && $statusChange == true) {
                        //code to handle if customer is just subscribed...

                        $this->_saveEventToModelTriggerEvents($configData, 'newsletter', $customerId);
                    }
                }
            }
        }
    }

    private function _saveEventToModelTriggerEvents($configData, $eventType, $customerId) {
        $points = Mage::helper('Boonagel_Alpesa')->processFlagPoints($configData, $eventType);
        $amount = Mage::helper('Boonagel_Alpesa')->pointPriceConversion($configData, $points, '', 'pnt-curr');
        //insert the points
        $dbpointsdata = Mage::helper('Boonagel_Alpesa')->savePointsToPointsModel($customerId, $amount, $points, 1);
        //trigger other events
        //trigger an event to process points conditional target
        Mage::helper('Boonagel_Alpesa')->triggerEventPointsUpdated($customerId);

        //trigger an event to process actual points
        Mage::helper('Boonagel_Alpesa')->triggerUpdateActualPoints($customerId, $dbpointsdata->getId());
    }

    //order saved thus spending-session
    public function orderSavedEvent($observer) {

        $customer = Mage::getSingleton('customer/session')->getCustomer();
        $order = $observer['order'];
        $orderId = $order->getId();
        $orderTotalAmount = $order->getGrandTotal();
        $configData = Mage::helper('Boonagel_Alpesa')->getConfigData();
        if ($customer != null) {

            if (count($configData) == 1) {

                $customerId = $customer->getId();

                $totalPoints = Mage::helper('Boonagel_Alpesa')->pointPriceConversion($configData, '', $orderTotalAmount, 'curr-pnt');
                //update user logs for spending-session
                $alpesauser = Mage::getModel('alpesa/alpesauser');
                $alpesauser->getData();
                $alpesauser->setLogType('spending-session');
                $alpesauser->setUserId($customerId);
                $alpesauser->setSessionAmount($orderTotalAmount);
                $alpesauser->setOrderNumber($orderId);
                $alpesauser->setUpdatedAt(now());
                $alpesauser->setcreatedAt(now());
                $dbuserlogs = $alpesauser->save();

                //update user points to available
                $dbpointsdata = Mage::helper('Boonagel_Alpesa')->savePointsToPointsModel($customerId, $orderTotalAmount, $totalPoints, 0, $orderId);
            }
        }

        $customerId = $customer != null ? $customer->getId() : 0;
        //ensure the customer is not registered before awarding them
        if ($customerId == 0) {

            //ensure config has been configured
            if (count($configData) == 1 && Mage::helper('Boonagel_Alpesa')->processFlagPoints($configData, 'referral') != 0) {
                //confirm if referral program is valid
                //confirm if cookie exists if not dont continue execution since its not a referral program
                $refActCookie = Mage::getModel('core/cookie')->get('alpesarefcookie');
                if (strlen(trim($refActCookie)) > 0) {
                    //destroy the cookie if it exists
                    Mage::getModel('core/cookie')->delete('alpesarefcookie');
                    //confirm if user is registered and has been logged to avoid awarding them again
                    $customerData = Mage::helper('Boonagel_Alpesa')->dynoData('alpesa/alpesarefcustomer', array('customer_id,eq,' . $customerId), 1);
                    if ($customerData->count() < 1) {
                        // award points since this user has never been awarded points before
                        $refereeData = Mage::helper('Boonagel_Alpesa')->dynoData('alpesa/alpesarefcodes', array('code,eq,' . $refActCookie), 1);
                        if ($refereeData->count() == 1) {
                            //award points
                            $referee = $refereeData->getFirstItem();
                            $alpesarefpoints = Mage::getModel('alpesa/alpesarefpoints');
                            $alpesarefpoints->getData();
                            $alpesarefpoints->setActual(0);
                            $alpesarefpoints->setCustomerId($referee->getCustomerId());
                            $alpesarefpoints->setOrderId($orderId);
                            $alpesarefpoints->setPoints(Mage::helper('Boonagel_Alpesa')->processFlagPoints($configData, 'referral'));
                            $alpesarefpoints->setUpdatedAt(now());
                            $alpesarefpoints->setcreatedAt(now());
                            $dbdata = $alpesarefpoints->save();

                            //update user points to available
                            $dbpointsdata = Mage::helper('Boonagel_Alpesa')->savePointsToPointsModel($referee->getCustomerId(), $orderTotalAmount, Mage::helper('Boonagel_Alpesa')->processFlagPoints($configData, 'referral'), 0, $orderId);

                            //this code wont be executed either way
                            if ($customerId != 0) {
                                //register this user if id is not 0 in our referee logs
                                $alpesarefcustomer = Mage::getModel('alpesa/alpesarefcustomer');
                                $alpesarefcustomer->getData();
                                $alpesarefcustomer->setCustomerId($customerId);
                                $alpesarefcustomer->setRefereeId($referee->getCustomerId());
                                $alpesarefcustomer->setUpdatedAt(now());
                                $alpesarefcustomer->setcreatedAt(now());
                                $dbdata = $alpesarefcustomer->save();
                            }
                        }
                    }
                }
            }
        }
    }

    ///the order was paid for
    public function orderComplete($observer) {
        
        //get the invoice
        $invoice = $observer->getEvent()->getInvoice();
        $order = $invoice->getOrder();
        $orderId = $order->getId();
        $incrementId = $order->getIncrementId();
        //if invoice already created then continue
        if (!$order->canInvoice()) {

            $customerId = 0;
            //change status for complete transaction in the logs
            $alpesauser = Mage::getModel('alpesa/alpesauser')->load($orderId, 'order_number');
            $alpesauser->setCompleteTransaction(1);
            $alpesauser->save();
            //update points status to actual points
            $alpesapoints = Mage::helper('Boonagel_Alpesa')->dynoData('alpesa/alpesapoints', array('order_number,eq,' . $orderId));
            if ($alpesapoints->count() > 0) {
                foreach ($alpesapoints as $alpesapoint) {
                    $customerId = $alpesapoint->getUserId() != 0 ? $alpesapoint->getUserId() : 0;
                    $alpesapoint->setStatus(1);
                    $alpesapoint->save();

                    //update referral table to actual
                    if ($customerId != 0) {
                        $this->_updateReferalPointStatus($orderId);
                    }
                    //$customerId = $alpesapoints->getUserId();
                    //trigger an event to process points conditional target
                    Mage::helper('Boonagel_Alpesa')->triggerEventPointsUpdated($customerId);

                    //trigger an event to process actual points
                    Mage::helper('Boonagel_Alpesa')->triggerUpdateActualPoints($customerId, $alpesapoint->getId());
                }
            }
        }

        //get the order and set the total paid to grandtotal if it has any logs in alpesa

        /** $alpesaInvoice = Mage::getModel('alpesa/alpesainvoice')->load($orderId, 'order_id');
          $alpesaVoucher = Mage::getModel('alpesa/alpesavoucher')->load($orderId, 'order_id');

          if (count($alpesaInvoice) > 0 || count($alpesaVoucher) > 0) {
          //then it exists in our module therefore set the total paid equal to the grandtotal
          $orders = Mage::getModel('sales/order')->getCollection()->addFieldToFilter('increment_id', array("eq" => $incrementId))->setPageSize(1);
          if ($orders->count() == 1) {

          $order = $orders->getFirstItem();
          $grandTotal = $order->getGrandTotal();
          $order->setTotalPaid($grandTotal);
          $order->save();
          }
          } * */
    }

    private function _updateReferalPointStatus($orderId) {
        $alpesarefpoints = Mage::helper('Boonagel_Alpesa')->dynoData('alpesa/alpesarefpoints', array('order_id,eq,' . $orderId));
        if ($alpesarefpoints->count() > 0) {
            foreach ($alpesarefpoints as $alpesarefpoint) {
                $alpesarefpoint->setActual(1);
                $alpesarefpoint->save();
            }
        }
    }

    public function invoiceSaved($observer) {
        $_invoice = $observer->getEvent()->getInvoice();
        $_order = $_invoice->getOrder();
        //$_order = $observer->getOrder();
//        if ($_invoice->getUpdatedAt() == $_invoice->getCreatedAt()) {

        $orderId = $_order->getId();
        $alpesaInvoice = Mage::getModel('alpesa/alpesainvoice')->load($orderId, 'order_id');
        $alpesaVoucher = Mage::getModel('alpesa/alpesavoucher')->load($orderId, 'order_id');

        if (count($alpesaInvoice) > 0 || count($alpesaVoucher) > 0) {
            //then it exists in our module therefore set the total paid equal to the grandtotal
            $incrementId = $_order->getIncrementId();
            $orders = Mage::getModel('sales/order')->getCollection()->addFieldToFilter('increment_id', array("eq" => $incrementId))->setPageSize(1);
            if ($orders->count() == 1) {

                Mage::log('wowwel');
                $order = $orders->getFirstItem();
                $grandTotal = $order->getGrandTotal();
                $order->setTotalPaid($grandTotal);
                $order->save();
            }
        }
//        } else {
//
//            // Logic for when invoice is updated
//        }
    }

}
