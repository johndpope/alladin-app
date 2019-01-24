<?php

class Boonagel_Cba_Adminhtml_CbaController extends Mage_Adminhtml_Controller_Action {

    public function configAction() {
        $this->loadLayout()
                ->_setActiveMenu('cbatab')
                ->_title($this->__('Cba Configuration'));

        $this->renderLayout();
    }

    public function logsAction() {
        $this->loadLayout()
                ->_setActiveMenu('cbatab')
                ->_title($this->__('Cba Logs'));

        $this->renderLayout();
    }

    public function advancedAction() {

        //determine if is ajax
        if ($this->getRequest()->isXmlHttpRequest()) {
            $this->getResponse()->setBody($this->getLayout()->createBlock('cba/adminhtml_logs_advanced_grid')->toHtml());
            return $this;
        }

        $this->loadLayout()
                ->_setActiveMenu('cbatab')
                ->_title($this->__('Cba Advanced Logs'));

        $this->_addContent($this->getLayout()->createBlock('cba/adminhtml_logs_advanced'));
        $this->renderLayout();
    }

    public function saveAction() {
        $data = $this->getRequest()->getPost();

        if ($data != null) {
            //process the post data for static rules and save to the database.
            $this->_saveStaticData($data);
            Mage::getSingleton('core/session')->addSuccess('Configuration data saved successfuly.');
        } else {
            Mage::getSingleton('core/session')->addError('Invalid data submited');
        }

        //redirect back to the configuration page
        $this->_redirect('*/*/config');
    }

    private function _saveStaticData($data) {

        $username = $data['username'];
        $password = $data['password'];
        $secret = $data['secret'];
        $config_id = $data['config_id'];
        $responseGateway = $data['response_gateway'];
        $updatedAt = now();
        $createdAt = now();

        $cbaconfig = Mage::getModel('cba/cbaconfig');
        $cbaconfig->load($config_id, 'id');
        $cbaconfig->setUsername($username);
        $cbaconfig->setPassword($password);
        $cbaconfig->setSecret($secret);
        $cbaconfig->setResponseGateway($responseGateway);
        $cbaconfig->setUpdatedAt($updatedAt);
        $cbaconfig->setcreatedAt($createdAt);
        $dbdata = $cbaconfig->save();

       
    }

}
