<?php

class Boonagel_Alpesa_CustomerController extends Mage_Core_Controller_Front_Action {

    /**
     * Check customer authentication
     */
    public function preDispatch() {
        parent::preDispatch();

        $loginUrl = Mage::helper('customer')->getLoginUrl();

        if (!Mage::getSingleton('customer/session')->authenticate($this, $loginUrl)) {
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
        }
    }

    public function reedemAction() {

        $this->loadLayout();
        Mage::helper('Boonagel_Alpesa')->setTitle($this, "Reedem Alpesa");
        $this->renderLayout();
    }

    public function walletAction() {


        $this->loadLayout();
        Mage::helper('Boonagel_Alpesa')->setTitle($this, "Wallet Alpesa");
        $this->renderLayout();
    }

    public function feedbackAction() {
        $this->loadLayout();
        Mage::helper('Boonagel_Alpesa')->setTitle($this, "Feedback Alpesa");
        $this->renderLayout();
    }

    public function summaryAction() {
        $this->loadLayout();
        Mage::helper('Boonagel_Alpesa')->setTitle($this, "Summary Alpesa");
        $this->renderLayout();
    }

    public function historyAction() {
        $this->loadLayout();
        Mage::helper('Boonagel_Alpesa')->setTitle($this, "History Alpesa");
        $this->renderLayout();
    }

    public function reedemprocessAction() {

        $postData = $this->getRequest()->getPost();

        $submittedPoints = $postData['reedemable_points'];

        $customerId = Mage::getSingleton('customer/session')->getCustomer()->getId();
//wallet update reducing the points and increasing the amount
        $alpesawalleted = Mage::getModel('alpesa/alpesawallet');
        $alpesawalleted->load($customerId, 'user_id');

//increase amount
        $configData = Mage::helper('Boonagel_Alpesa')->getConfigData();
//confirm whether the point meets the percentage limit.
        $reedemablePoints = round((($configData->percentage_reedemable / 100) * $alpesawalleted->actual_points));
        $points = $submittedPoints <= $reedemablePoints && $submittedPoints >= 0 ? $submittedPoints : $reedemablePoints;

        if ($points > 0) {

            $reedemedAmnt = Mage::helper('Boonagel_Alpesa')->pointPriceConversion($configData, $points, '', 'pnt-curr');

            $totalAmt = $alpesawalleted->wallet + $reedemedAmnt;
//reduce points
            $currentPnts = $alpesawalleted->actual_points - $points;
            $totalPnts = $currentPnts < 0 ? 0 : $currentPnts;


            if ($totalPnts >= $configData->minimum_points) {
                $alpesawallet = Mage::getModel('alpesa/alpesawallet');
                $alpesawallet->load($customerId, 'user_id');
                $alpesawallet->setUserId($customerId);
                $alpesawallet->setActualPoints($totalPnts);
                $alpesawallet->setWallet($totalAmt);
                $alpesawallet->setUpdatedAt(now());
                $alpesawallet->setcreatedAt(now());
                $dbdata = $alpesawallet->save();

//log the amount and points reedemed.
                $alpesaredeem = Mage::getModel('alpesa/alpesaredeem');
                $alpesaredeem->getData();
                $alpesaredeem->setUserId($customerId);
                $alpesaredeem->setPoints($points);
                $alpesaredeem->setAmount($reedemedAmnt);
                $alpesaredeem->setStatus(1);
                $alpesaredeem->setUpdatedAt(now());
                $alpesaredeem->setcreatedAt(now());
                $dbdata = $alpesaredeem->save();

                Mage::getSingleton('core/session')->addSuccess('You have successfuly reedemed points');
            } else {
                Mage::getSingleton('core/session')->addError('The minimum amount of points allowed is ' . $configData->minimum_points);
            }
        }
//redirect back to the reedem page
        $this->_redirect('*/*/reedem');
    }

    public function invoiceAction() {

        //ensure paymets is allowed
        if (Mage::helper('Boonagel_Alpesa')->allowPayments() != true) {
            /*             * do not allow paying* */
            return $this->_redirect('*/*/wallet');
        }


        //echo 'This feature has been temporarily disabled';
        //die();

        if (!$this->getRequest()->isPost()) {
//redirect back to the wallet section
            return $this->_redirect('*/*/wallet');
        }

        $data = $this->getRequest()->getPost();

        if (count($data) < 1) {
            return $this->_redirect('*/*/wallet');
        }
//confirm the value ie voucher,points
        if (!in_array($data['alpesapaymenttype'], array('points', 'voucher'))) {
//redirect back to the wallet section
            return $this->_redirect('*/*/wallet');
        }

//confirm id exists of a card
        if ($data['alpesapaymenttype'] == 'voucher') {
            $cardData = Mage::helper('Boonagel_Alpesa')->dynoData('alpesa/alpesacard', array('id,eq,' . $data['alpesacardid']), 1);
            if ($cardData->count() != 1) {
                $this->_redirect('*/*/wallet');
            }
        }

//get customer id
        $customerId = Mage::getSingleton('customer/session')->getCustomer()->getId();
//confirm the wallet has money
        if ($data['alpesapaymenttype'] == 'points') {
            $walletData = Mage::helper('Boonagel_Alpesa')->dynoData('alpesa/alpesawallet', array('user_id,eq,' . $customerId), 1);
            if ($walletData->count() != 1) {
                return $this->_redirect('*/*/wallet');
            } elseif ($walletData->getFirstItem()->wallet < 1) {
                return $this->_redirect('*/*/wallet');
            }
        }

//pass the necessary variables to the block ie through registering
//type of payment ie points,voucher,cardid
        Mage::register('alpesapaymenttype', $data['alpesapaymenttype']);
        if ($data['alpesapaymenttype'] == 'voucher') {
            Mage::register('alpesacardid', $data['alpesacardid']);
        }


        $this->loadLayout();
        $this->renderLayout();
    }

    public function payAction() {
        //echo 'This feature has been temporarily disabled';
        //die();
        /*         * $thisOrder = Mage::getModel("sales/order")->load(11);
          $thisOrder->setTotalPaid(0)->save();

          die();* */


        //ensure paymets is allowed
        if (Mage::helper('Boonagel_Alpesa')->allowPayments() != true) {
            /*             * do not allow paying* */
            return $this->_redirect('*/*/wallet');
        }
//ensure it is a post request.
        if (!$this->getRequest()->isPost()) {
//redirect back to the wallet section
            return $this->_redirect('*/*/wallet');
        }

//get all the invoice ids
        $data = $this->getRequest()->getPost();
        $invoicesids = $data['invoice_ids'];
        $arrayedOrderIds = explode(",", $invoicesids);
        if (count($arrayedOrderIds) > 0) {

            foreach ($arrayedOrderIds as $arrayedOrderId) {
//check whether they exist in post request
                if (array_key_exists($arrayedOrderId, $data)) {
//perform the processing of this specific invoice
                    $this->_processPayGoods($data, $arrayedOrderId);
                }
            }
        }


//redirect to wallet section with a message notification. and a link to view all orders
        Mage::getSingleton('core/session')->addSuccess('Orders successfuly paid for.');
        return $this->_redirect('*/*/wallet');
    }

    private function _processPayGoods($postData, $orderId) {

//get customer id
        $customerId = Mage::getSingleton('customer/session')->getCustomer()->getId();
//get all the orders
        $thisOrder = Mage::getModel("sales/order")->load($orderId);
//get the points wallet
        $walletData = Mage::helper('Boonagel_Alpesa')->dynoData('alpesa/alpesawallet', array('user_id,eq,' . $customerId), 1);
        $walletData = $walletData->getFirstItem();

//perform mathematical calculations and confirm the invoice
        if (count($thisOrder) == 1) {
//subtract the wallet amount from grandtotal
            $grandTotal = $thisOrder->grand_total;
            $totalPaid = $thisOrder->total_paid;
//$totalDue = $thisOrder->total_due;
            $finalTotalPaid = 0;

            $walletAmt = $walletData->wallet;

//execute points here-payment type
            if ($postData['payment_type'] == 'points') {
                if ($walletAmt > 0) {
                    /** $myTotalPaid = ($totalPaid + $walletData->wallet);
                      $myFinTotalPaid = $myTotalPaid > $grandTotal ? $grandTotal : $myTotalPaid;
                      $walletUsed = $myFinTotalPaid - $totalPaid;
                      $walletRemainder = ($walletData->wallet - $walletUsed);* */
                    if ($walletAmt >= $grandTotal) {
                        //update history
                        $paymentDate = now();
                        $comment = ' Amount : ' . $grandTotal .
                                ', Paid on : ' . $paymentDate
                                . ', Through : Alpesa.';
                        $thisOrder->setStatus('Alpesa Payment By Actual Amount');
                        $thisOrder->addStatusToHistory('alpesa_payment_processing', $comment, false)->setIsVisibleOnFront(1);
                        $thisOrder->save();
                        //$thisOrder->setTotalPaid($myFinTotalPaid)->save();
                        $walletRemainder = $walletAmt - $grandTotal;
//update wallet
                        $this->_updateWallet($walletData->id, $walletRemainder);

//update the logs
                        $this->_logPayments($postData, $customerId, $orderId, $grandTotal, '', '', '');
                    }
                }
            }


//execute voucher here-payment type
            if ($postData['payment_type'] == 'voucher') {
//get the voucher card amount

                $alpesacard = Mage::getModel('alpesa/alpesacard')->load($postData['card_id']);
//ensure its today and used_voucher_amts does not add upto the total voucher amt
                if (count($alpesacard) == 1) {
                    $voucherValidity = Mage::helper('Boonagel_Alpesa')->giftFlag($alpesacard->id);

                    if ($voucherValidity[0] == true) {

                        $voucherAmt = $alpesacard->card_voucher_amount;
//get the total_paid and add voucher amount
                       /** $mineTotalPaid = ($totalPaid + $voucherAmt);
//confirm its not more than the grand total
                        $finalTotalPaid = $mineTotalPaid > $grandTotal ? $grandTotal : $mineTotalPaid;
//get the totalVoucher used
                        $voucherUsed = $finalTotalPaid - $totalPaid;**/
//create invoice if total_paid== grandtotal

                        if ($voucherAmt >= $grandTotal) {
                             //update history
                        $paymentDate = now();
                        $comment = ' Amount : ' . $grandTotal .
                                ', Paid on : ' . $paymentDate
                                . ', Through : Alpesa.';
                        $thisOrder->setStatus('Alpesa Payment By Voucher');
                        $thisOrder->addStatusToHistory('alpesa_payment_processing', $comment, false)->setIsVisibleOnFront(1);
                        $thisOrder->save();
//update logs
                        $this->_logPayments($postData, $customerId, $orderId, '', $grandTotal, $voucherAmt, $grandTotal);
                        
                        }

                    }
                }
            }
        }

//Zend_Debug::dump($thisOrder, 'me too');
//die();
    }

//create on complete payment
    private function _createInvoice($incrementId) {

//set invoice as paid if it exists or automatically create one to show it has been paid

        $orders = Mage::getModel('sales/order')->getCollection()->addFieldToFilter('increment_id', array("eq" => $incrementId))->setPageSize(1);

//        $order = Mage::getModel("sales/order")->load('increment_id',$incrementId);
        if ($orders->count() == 1) {
            $order = $orders->getFirstItem();


            try {

                if ($order->canInvoice()) {

                    $invoice = Mage::getModel('sales/service_order', $order)->prepareInvoice();
                    if ($invoice->getTotalQty()) {



                        $invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_OFFLINE);
                        $invoice->register();
                        $invoice->getOrder()->setCustomerNoteNotify(false);
                        $invoice->getOrder()->setIsInProcess(true);
                        $order->addStatusHistoryComment('Automatically INVOICED by Alpesa.', false);

                        $transactionSave = Mage::getModel('core/resource_transaction')
                                ->addObject($invoice)
                                ->addObject($invoice->getOrder());

                        $transactionSave->save();

//$this->_setOrderState($incrementId,Mage_Sales_Model_Order::STATE_PROCESSING);

                        Mage::getSingleton('core/session')->addSuccess('Invoice Created Successfully.');
                    } else {
                        Mage::getSingleton('core/session')->addError('Can not create invoice without Product Quantities');
                    }
                } else {
                    Mage::getSingleton('core/session')->addError('Failed to create invoice.');
                }
            } catch (Mage_Core_Exception $e) {
                echo $e->getMessage();
            }
        }
    }

    /*     * change the order state and status from* */

    private function _setOrderState($incrementId, $state) {
//$state = Mage_Sales_Model_Order::STATE_PROCESSING

        $meorders = Mage::getModel('sales/order')->getCollection()->addFieldToFilter('increment_id', array("eq" => $incrementId))->setPageSize(1);

//set the status as processing
        $meordersFirst = $meorders->getFirstItem();

//setStatus and setState
        $final = $meordersFirst->setState($state)->setStatus($state)->save();
//         echo $final->getStatus();
//        DIE();
    }

//udpate logs
    private function _logPayments($postData, $customerId, $orderId, $usedAmt, $grandTotal, $voucheramount, $voucherUsed) {

//log all the vouchers and used amounts
//'points', 'voucher'
        $paymentType = $postData['payment_type'];

        if ($paymentType == 'points') {
            $alpesainvoice = Mage::getModel('alpesa/alpesainvoice');
            $alpesainvoice->getData();
            $alpesainvoice->setUserId($customerId);
            $alpesainvoice->setOrderId($orderId);
            $alpesainvoice->setUsedAmount($usedAmt);
            $alpesainvoice->setUsedDate(now());
            $alpesainvoice->setUpdatedAt(now());
            $alpesainvoice->setcreatedAt(now());
            $dbdata = $alpesainvoice->save();
        }

        if ($paymentType == 'voucher') {
            $alpesavoucher = Mage::getModel('alpesa/alpesavoucher');
            $alpesavoucher->getData();
            $alpesavoucher->setUserId($customerId);
            $alpesavoucher->setOrderId($orderId);
            $alpesavoucher->setVoucherAmount($voucheramount);
            $alpesavoucher->setOrderAmount($grandTotal);
            $alpesavoucher->setVoucherUsedAmount($voucherUsed);
            $alpesavoucher->setVoucherDate(now());
            $alpesavoucher->setUpdatedAt(now());
            $alpesavoucher->setcreatedAt(now());
            $dbdata = $alpesavoucher->save();
        }
    }

//update wallet amount
    private function _updateWallet($walletId, $walletAmt) {
//update wallet
        $alpesawalenje = Mage::getModel('alpesa/alpesawallet')->load($walletId);
        $alpesawalenje->setWallet($walletAmt);
        $alpesawalenje->save();
    }

}
