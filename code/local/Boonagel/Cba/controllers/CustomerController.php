<?php

class Boonagel_Cba_CustomerController extends Mage_Core_Controller_Front_Action {

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

   
    public function mpesaAction() {

        $this->loadLayout();
         Mage::helper('Boonagel_Cba')->setTitle($this,"Mpesa Transactions");
        $this->renderLayout();
    }
  public function logsAction() {

        $this->loadLayout();
         Mage::helper('Boonagel_Cba')->setTitle($this,"Payment Logs");
        $this->renderLayout();
    }


}
