<?php

class Boonagel_Direct_CustomerController extends Mage_Core_Controller_Front_Action {

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

  public function logsAction() {

        $this->loadLayout();
         Mage::helper('Boonagel_Direct')->setTitle($this,"DirectPayOnline Payment Logs");
        $this->renderLayout();
    }


}
