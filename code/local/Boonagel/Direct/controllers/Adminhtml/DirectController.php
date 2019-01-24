<?php

class Boonagel_Direct_Adminhtml_DirectController extends Mage_Adminhtml_Controller_Action {

    public function logsAction() {
        $this->loadLayout()
                ->_setActiveMenu('directtab')
                ->_title($this->__('DirectPayOnline Logs'));

        $this->renderLayout();
    }

    public function advancedAction() {

        //determine if is ajax
        if ($this->getRequest()->isXmlHttpRequest()) {
            $this->getResponse()->setBody($this->getLayout()->createBlock('direct/adminhtml_logs_advanced_grid')->toHtml());
            return $this;
        }

        $this->loadLayout()
                ->_setActiveMenu('directtab')
                ->_title($this->__('DirectPayOnline Advanced Logs'));

        $this->_addContent($this->getLayout()->createBlock('direct/adminhtml_logs_advanced'));
        $this->renderLayout();
    }

}
