<?php

class Boonagel_Alpesa_Adminhtml_AlpesaController extends Mage_Adminhtml_Controller_Action {

    public function indexAction() {
        $this->loadLayout()
                ->_setActiveMenu('alpesatab')
                ->_title($this->__('Alpesa Users'));

        $this->renderLayout();
    }

    public function usersAction() {
        $this->loadLayout()
                ->_setActiveMenu('alpesatab')
                ->_title($this->__('Alpesa Users'));

        $this->renderLayout();
    }

    public function configurationAction() {
        $this->loadLayout()
                ->_setActiveMenu('alpesatab')
                ->_title($this->__('Alpesa Configuration'));

        $this->renderLayout();
    }

    public function analysisAction() {
        $this->loadLayout()
                ->_setActiveMenu('alpesatab')
                ->_title($this->__('Alpesa Wallet Analysis'));

        $this->renderLayout();
    }

    public function paymentsAction() {
        //determine if is ajax
        if ($this->getRequest()->isXmlHttpRequest()) {
            $this->getResponse()->setBody($this->getLayout()->createBlock('alpesa/adminhtml_logs_wallet_grid')->toHtml());
            return $this;
        }

        $this->loadLayout()
                ->_setActiveMenu('alpesatab')
                ->_title($this->__('Alpesa Wallet Payment Logs'));

        $this->_addContent($this->getLayout()->createBlock('alpesa/adminhtml_logs_wallet'));
        $this->renderLayout();
    }

    /*     * payment through wallet* */

    public function walletAction() {
        $id = trim(Mage::app()->getRequest()->getParam('id'));
        if ($id == null) {
            $this->_redirect('*/*/payments');
            return;
        }

        $alpesainvoices = Mage::helper('Boonagel_Alpesa')->dynoData('alpesa/alpesainvoice', array('id,eq,' . $id), 1);
        if ($alpesainvoices->count() != 1) {
            $this->_redirect('*/*/payments');
            return;
        }
        $alpesainvoice = $alpesainvoices->getFirstItem();

        //already validated
        if ($alpesainvoice->getValidated() == 1) {
            $this->_redirect('*/*/payments');
            return;
        }
        $alpesainvoice->setValidated(1);
        $alpesainvoice->save();
        //change to validated and update order status
        $order = Mage::getModel("sales/order")->load($alpesainvoice->getOrderId());
        if (count($order) != 1) {
            $this->_redirect('*/*/payments');
            return;
        }

        $comment = ' Amount : ' . $order->grand_total
                . ', Through : Alpesa.';
        $order->setStatus('Alpesa Payment By Actual Amount');
        $order->addStatusToHistory('alpesa_payment_complete', $comment, false)->setIsVisibleOnFront(1);
        $order->save();
        $this->_redirect('*/*/payments');
    }

    public function voucherAction() {
        //determine if is ajax
        if ($this->getRequest()->isXmlHttpRequest()) {
            $this->getResponse()->setBody($this->getLayout()->createBlock('alpesa/adminhtml_logs_voucher_grid')->toHtml());
            return $this;
        }

        $this->loadLayout()
                ->_setActiveMenu('alpesatab')
                ->_title($this->__('Alpesa Voucher Payment Logs'));

        $this->_addContent($this->getLayout()->createBlock('alpesa/adminhtml_logs_voucher'));
        $this->renderLayout();
    }

    /*     * payment through wallet* */

    public function voucherpayAction() {
        $id = trim(Mage::app()->getRequest()->getParam('id'));
        if ($id == null) {
            $this->_redirect('*/*/voucher');
            return;
        }

        $alpesavouchers = Mage::helper('Boonagel_Alpesa')->dynoData('alpesa/alpesavoucher', array('id,eq,' . $id), 1);
        if ($alpesavouchers->count() != 1) {
            $this->_redirect('*/*/voucher');
            return;
        }
        $alpesavoucher = $alpesavouchers->getFirstItem();

        //already validated
        if ($alpesavoucher->getValidated() == 1) {
            $this->_redirect('*/*/voucher');
            return;
        }
        $alpesavoucher->setValidated(1);
        $alpesavoucher->save();
        //change to validated and update order status
        $order = Mage::getModel("sales/order")->load($alpesavoucher->getOrderId());
        if (count($order) != 1) {
            $this->_redirect('*/*/voucher');
            return;
        }

        $comment = ' Amount : ' . $order->grand_total
                . ', Through : Alpesa.';
        $order->setStatus('Alpesa Payment By Voucher');
        $order->addStatusToHistory('alpesa_payment_complete', $comment, false)->setIsVisibleOnFront(1);
        $order->save();
        $this->_redirect('*/*/voucher');
    }

    //offline link
    public function offlineAction() {

//        $customers  = Mage::getModel('customer/customer')->getCollection();
//        foreach($customers as $customer){
//            Zend_Debug::dump($customer->debug());
//        }
//        
        //echo var_dump($customers->count());
//        die();
        //determine if is ajax
        if ($this->getRequest()->isXmlHttpRequest()) {
            $this->getResponse()->setBody($this->getLayout()->createBlock('alpesa/adminhtml_logs_customer_grid')->toHtml());
            return $this;
        }

        $this->loadLayout()
                ->_setActiveMenu('alpesatab')
                ->_title($this->__('Alpesa Offline Customers'));

        $this->_addContent($this->getLayout()->createBlock('alpesa/adminhtml_logs_customer'));
        $this->renderLayout();
    }

    //offline pay link
    public function offlineaccessAction() {

        $id = trim(Mage::app()->getRequest()->getParam('id'));
        if ($id == null) {
            $this->_redirect('*/*/voucher');
            return;
        }
        Mage::register('entityId', $id);

        $this->loadLayout()
                ->_setActiveMenu('alpesatab')
                ->_title($this->__('Alpesa Offline Access'));

        $this->renderLayout();
    }

    public function offlinepayAction() {

        $data = $this->getRequest()->getPost();
        $email = $data['email'] ? trim($data['email']) : null;
        $order_id = $data['order_id'] ? trim($data['order_id']) : null;
        $amount = $data['amount'] ? trim($data['amount']) : null;
        $password = $data['password'] ? trim($data['password']) : null;

        if ($email == null || $order_id == null || $amount == null || $password == null) {
            $this->_redirect('*/*/offline');
        }

//        $websiteId = Mage::app()->getWebsite()->getId();
//       $value =  Mage::getModel('customer/customer')->setWebsiteId($websiteId)->authenticate($email, $password);
//       echo var_dump($value);die();
//        $customer = Mage::getModel('customer/customer')
//        ->setWebsiteId(Mage::app()->getStore()
//        ->getWebsiteId())
//        ->loadByEmail($email);
        
        
        $websiteId = Mage::app()->getWebsite()->getId();
        $store = Mage::app()->getStore();
        $customer = Mage::getModel("customer/customer");
        $customer->website_id = $websiteId;
        $customer->setStore($store);
        $customer->loadByEmail($email);
        $hash = $customer->getData('password_hash');
        //Zend_Debug::dump($customer);
        Zend_Debug::dump($customer->getData());
       //echo var_dump($hash);
        die();
        $hashPassword = explode(':', $hash);
        $firstPart = $hashPassword[0];
        $salt = $hashPassword[1];

        $current_password = md5($salt . $password);
        if ($current_password != $firstPart) {
            echo 'they dont match';
        } else {
            echo 'they match';
        }
    }

    public function saveAction() {
        $data = $this->getRequest()->getPost();

        //process the post data for static rules and save to the database.
        $this->_saveStaticRules($data);

        //process the post data for card rules and save to the database.
        $this->_saveCardRules($data);

        //process the post data for conditional rules and save to the database.
        $this->_saveConditionalRules($data);

        //redirect back to the configuration page
        $this->_redirect('*/*/configuration');
    }

    private function _saveStaticRules($data) {

        $ruleType = 'static';
        $configPriority = 1;
        $percentageRedeemable = $data['percentage_reedemable'];
        $currencyPoint = $data['curr_to_points_curr'] . ',' . $data['curr_to_points_pnts'];
        $pointCurrency = $data['pnts_to_curr_points'] . ',' . $data['pnts_to_curr_curr'];
        $newsletterPoints = $data['newsletter_sign_up_flag'] . ',' . $data['newsletter_sign_up'];
        $signupPoints = $data['sign_up_flag'] . ',' . $data['sign_up_points'];
        //if login yes then no login interval value needed.
        $loginPoints = $data['login_flag'] . ',' . $data['login_points'];
        $loginInterval = '';
        if ($data['login_flag'] == 'yes') {
            $loginInterval = $data['login_interval'] . ',' . $data['login_interval_flag'];
        }
        $referralPoints = $data['referral_flag'] . ',' . $data['referral'];
        $minimumPoints = $data['minimum_points'];
        $allowedPayments = $data['allow_payment'];
        /*         * echo $allowedPayments;
          die();* */
        $updatedAt = now();
        $createdAt = now();

        $alpesaconfig = Mage::getModel('alpesa/alpesaconfig');
        $alpesaconfig->load(1, 'config_priority');
        $alpesaconfig->setRuleType($ruleType);
        $alpesaconfig->setConfigPriority($configPriority);
        $alpesaconfig->setPercentageReedemable($percentageRedeemable);
        $alpesaconfig->setCurrencyPoint($currencyPoint);
        $alpesaconfig->setPointCurrency($pointCurrency);
        $alpesaconfig->setNewsletterPoints($newsletterPoints);
        $alpesaconfig->setSignupPoints($signupPoints);
        $alpesaconfig->setLoginPoints($loginPoints);
        $alpesaconfig->setLoginInterval($loginInterval);
        $alpesaconfig->setReferralPoints($referralPoints);
        $alpesaconfig->setMinimumPoints($minimumPoints);
        $alpesaconfig->setAllowPayment($allowedPayments);
        $alpesaconfig->setUpdatedAt($updatedAt);
        $alpesaconfig->setcreatedAt($createdAt);
        $dbdata = $alpesaconfig->save();

        //trigger an event on points quantity change
    }

    private function _saveCardRules($data) {

        //delete all the card records
        $this->_deleteModelRecords('alpesa/alpesacard');


        //loop through each input value inserting
        $cardRulesData = trim($data['cardRulesIdInput']);

        if (strlen($cardRulesData) > 0) {
            $cardRulesDataArray = explode(",", $cardRulesData);
            foreach ($cardRulesDataArray as $inputId) {

                $cardName = $data['card_name_' . $inputId];
                $cardMinMaxPoints = $data['card_min_points_' . $inputId] . ',' . $data['card_max_points_' . $inputId];
                $cardColor = $data['card_color_' . $inputId];
                $cardDiscount = $data['card_prod_discount_' . $inputId] . ',' . $data['card_name_' . $inputId];
                $cardVoucherAmount = $data['card_voucher_amount_' . $inputId];

                //perfom some computations
                $cardGiftDateData = '';
                $periodVal = $data['period_flag_' . $inputId];
                if ($periodVal == 'annualy') {
                    //extract 4 items
                    $cardGiftDateData = $data['period_flag_' . $inputId] . ',' . $data['month_flag_' . $inputId] . ',' . $data['week_flag_' . $inputId] . ',' . $data['day_flag_' . $inputId];
                }
                if ($periodVal == 'monthly') {
                    //extract 3 items
                    $cardGiftDateData = $data['period_flag_' . $inputId] . ',' . $data['week_flag_' . $inputId] . ',' . $data['day_flag_' . $inputId];
                }
                if ($periodVal == 'weekly') {
                    //extract 2 items
                    $cardGiftDateData = $data['period_flag_' . $inputId] . ',' . $data['day_flag_' . $inputId];
                }

                $cardGiftDate = $cardGiftDateData;

                $updatedAt = now();
                $createdAt = now();

                $alpesaCardData = Mage::getModel('alpesa/alpesacard');
                $alpesaCardData->setCardName($cardName);
                $alpesaCardData->setCardMinMaxPoints($cardMinMaxPoints);
                $alpesaCardData->setCardColor($cardColor);
                $alpesaCardData->setCardDiscount($cardDiscount);
                $alpesaCardData->setCardGiftDate($cardGiftDate);
                $alpesaCardData->setCardVoucherAmount($cardVoucherAmount);
                $alpesaCardData->setUpdatedAt($updatedAt);
                $alpesaCardData->setcreatedAt($createdAt);
                $dbdata = $alpesaCardData->save();
            }
        }
    }

    private function _saveConditionalRules($data) {

        //delete all the conditional records
        $this->_deleteModelRecords('alpesa/alpesacondition');


        $conditionRulesConfig = trim($data['dynamicRulesIdInput']);
        $conditionRules = trim($data['conditionalRulesIdInput']);


        if (strlen($conditionRulesConfig) > 0) {

            $conditionRulesConfigArray = explode(",", $conditionRulesConfig);
            foreach ($conditionRulesConfigArray as $conditionConfigId) {

                //set the basic field values
                $conditionChild = false;

                $updatedAt = now();
                $createdAt = now();

                //get the model instance
                $alpesaConditionData = Mage::getModel('alpesa/alpesacondition');

                $alpesaConditionData->setConfigId($conditionConfigId);
                $alpesaConditionData->setPointsTarget($data['points_target_' . $conditionConfigId]);
                $alpesaConditionData->setPerVisitSpending($data['per_visit_spending_' . $conditionConfigId]);
                $alpesaConditionData->setPointsReward($data['dyno_point_reward_' . $conditionConfigId]);
                $alpesaConditionData->setUpdatedAt($updatedAt);
                $alpesaConditionData->setcreatedAt($createdAt);


                if (strlen($conditionRules) > 0) {
                    $conditionRulesArray = explode(",", $conditionRules);
                    foreach ($conditionRulesArray as $conditionalId) {
                        //loop through inputs,if value exists then set 

                        $childExists = false;
                        if (array_key_exists('condition_scope_' . $conditionConfigId . '_' . $conditionalId, $data)) {
                            $conditionChild = true;
                            $childExists = true;
                            $alpesaConditionData->setConditionScope($data['condition_scope_' . $conditionConfigId . '_' . $conditionalId]);
                        }

                        if (array_key_exists('condition_visits_' . $conditionConfigId . '_' . $conditionalId, $data)) {
                            $conditionChild = true;
                            $childExists = true;
                            $alpesaConditionData->setvisits($data['condition_visits_' . $conditionConfigId . '_' . $conditionalId]);
                        }

                        if (array_key_exists('condition_operator_' . $conditionConfigId . '_' . $conditionalId, $data)) {
                            $conditionChild = true;
                            $childExists = true;
                            $alpesaConditionData->setConditionOperator($data['condition_operator_' . $conditionConfigId . '_' . $conditionalId]);
                        }

                        if ($childExists == true) {
                            //save to the database
                            $alpesaConditionData->setConfigId($conditionConfigId);
                            $alpesaConditionData->setPointsTarget($data['points_target_' . $conditionConfigId]);
                            $alpesaConditionData->setPerVisitSpending($data['per_visit_spending_' . $conditionConfigId]);
                            $alpesaConditionData->setPointsReward($data['dyno_point_reward_' . $conditionConfigId]);
                            $alpesaConditionData->setUpdatedAt($updatedAt);
                            $alpesaConditionData->setcreatedAt($createdAt);
                            $dbdata = $alpesaConditionData->save();
                            $alpesaConditionData = Mage::getModel('alpesa/alpesacondition');
                        }
                    }
                }

                if ($conditionChild == false) {
                    //perfom your own save
                    $dbdata = $alpesaConditionData->save();
                }
            }
        }
    }

    private function _deleteModelRecords($modelType) {


        //delete all the card records
        $alpesaModel = Mage::getModel($modelType);
        $alpesaModelCreated = $alpesaModel->getCollection();

        if ($alpesaModelCreated->count() > 0) {
            foreach ($alpesaModelCreated as $alpesaModelCreation) {
                $alpesaModelCreation->delete();
            }
        }
    }

    public function confirm_paymentAction() {
        $data = $this->getRequest()->getPost();

        $orderId = $data['orderid'];

        //confirm it exists in unvalidated array collection
        $paymentsData = Mage::helper('Boonagel_Alpesa')->alpesaPayments();
        $unvalidated = $paymentsData['unvalidated'];
        if (!array_key_exists($orderId, $unvalidated)) {
            return $this->_redirect('*/*/payments');
        }

        //get the order
        $actOrder = Mage::getModel('sales/order')->load($orderId);
        if (count($actOrder) != 1) {
            return $this->_redirect('*/*/payments');
        }

        $invoiceId = 0;
        //process the invoice and set order status to processing
        $invoiceId = Mage::helper('Boonagel_Alpesa')->createInvoice($actOrder->increment_id);

        //set validated on those logs
        $thiSpecificLogs = $unvalidated[$orderId];
        $counted = count($thiSpecificLogs);
        if ($invoiceId != true) {
            for ($i = 4; $i < $counted; $i++) {
                $thisLog = $thiSpecificLogs[$i];
                if ($thisLog->amount_type == 'voucher') {
                    //voucherused model
                    $voucherLog = Mage::getModel('alpesa/alpesavoucher')->load($thisLog->id);
                    if (count($voucherLog) == 1) {
                        $voucherLog->setValidated(1)->save();
                    }
                }
                if ($thisLog->amount_type == 'points') {
                    //points used model
                    $walletPntLog = Mage::getModel('alpesa/alpesainvoice')->load($thisLog->id);
                    if (count($walletPntLog) == 1) {
                        $walletPntLog->setValidated(1)->save();
                    }
                }
            }
        }


        //redirect to this specific invoice created.
        return $this->_redirect('*/*/payments');
//        if ($invoiceId != true && $invoiceId != false) {
//            return $this->_redirect('*/sales_order_invoice/view', array('invoice_id' => $invoiceId, 'order_id' => $actOrder->id));
//        }
    }

}
