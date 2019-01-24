<?php
class Cminds_SupplierTrading_Adminhtml_TradesController extends Mage_Adminhtml_Controller_Action
{

    public function indexAction() {

        $this->_title($this->__('Trades'));
        $this->loadLayout();
        $this->_setActiveMenu('suppliers');
        $this->renderLayout();
    }


}
