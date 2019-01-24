<?php
class Cminds_Rma_Model_Rma_Comment extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('cminds_rma/rma_comment');
    }

    protected function _beforeSave()
    {
        if (!$this->getId()) {
            $this->isObjectNew(true);

            $this->setCreatedAt(date('Y-m-d H:i:s'));

        }
        Mage::dispatchEvent('model_save_before', array('object' => $this));
        Mage::dispatchEvent($this->_eventPrefix.'_save_before', $this->_getEventData());
        return $this;
    }

    protected function _afterSave()
    {
        if($this->getIsCustomerNotified()) {
            $this->_notifyCustomer();
        }

        $this->cleanModelCache();
        Mage::dispatchEvent('model_save_after', array('object'=>$this));
        Mage::dispatchEvent($this->_eventPrefix.'_save_after', $this->_getEventData());
        return $this;
    }

    public function getRma() {
        return Mage::getModel('cminds_rma/rma')->load($this->getRmaId());
    }

    protected function _notifyCustomer() {
        $rma = $this->getRma();
        $customer = $rma->getCustomer();
        $body = $this->getCommentBody();

        try {
            $emailTemplate  = Mage::getModel('core/email_template')
                ->loadDefault('rma_update');

            $emailTemplateVariables = array();
            $emailTemplateVariables['rma'] = $rma;
            $emailTemplateVariables['order'] = $rma->getOrder();
            $emailTemplateVariables['comment'] = $body;


            $emailTemplate->setSenderName(Mage::getStoreConfig('trans_email/ident_general/name'));
            $emailTemplate->setSenderEmail(Mage::getStoreConfig('trans_email/ident_general/email'));

            $emailTemplate->getProcessedTemplate($emailTemplateVariables);
            $emailTemplate->send($customer->getEmail(),$customer->getFirstname() .' '. $customer->getLastname(), $emailTemplateVariables);
        }
        catch (Exception $e) {
            Mage::getSingleton('core/session')->addError($e->getMessage());
        }
    }
}
