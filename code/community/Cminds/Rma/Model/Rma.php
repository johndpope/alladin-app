<?php
class Cminds_Rma_Model_Rma extends Mage_Core_Model_Abstract
{
    const DEFAULT_SORT = 'ASC';
    const DEFAULT_OPEN_ID = 1;
    const DEFAULT_CLOSED_ID = 2;
    const DEFAULT_CANCELED_ID = 3;

    protected $_eventPrefix = 'cminds_rma';


    protected function _construct()
    {
        $this->_init('cminds_rma/rma');
    }

    public function getStatusLabel()
    {
        $status = Mage::getModel('cminds_rma/rma_status')->load($this->getStatusId());

        return $status->getName();
    }

    public function isClosed()
    {
        $status = Mage::getModel('cminds_rma/rma_status')->load($this->getStatusId());

        return $status->getIsClosing() == 1;
    }

    public function close()
    {
        $this->setStatusId(self::DEFAULT_CLOSED_ID);
        $this->setIsClosed(1);
        $this->save();
    }

    public function getTypeLabel()
    {
        $status = Mage::getModel('cminds_rma/rma_type')->load($this->getTypeId());

        return $status->getName();
    }

    public function getReasonLabel()
    {
        $status = Mage::getModel('cminds_rma/rma_reason')->load($this->getReasonId());

        return $status->getName();
    }

    public function getFormattedCreatedAt()
    {
        $date = new DateTime($this->getCreatedAt());
        return $date->format('m/d/Y');
    }

    public function getAllItems()
    {
        return Mage::getModel('cminds_rma/rma_item')->getCollection()->addFieldToFilter('rma_id', $this->getId());
    }

    public function getAllStatusHistory()
    {
        return Mage::getModel('cminds_rma/rma_comment')
            ->getCollection()
            ->addFieldToFilter('rma_id', $this->getId())
            ->setOrder('created_at');
    }

    public function getMemoCollection()
    {
        $col = Mage::getModel('sales/order_creditmemo')
            ->getCollection()
            ->addFieldToFilter('order_id', $this->getOrderId());
        return $col;
    }

    public function getOrder()
    {
        return Mage::getModel('sales/order')->load($this->getOrderId());
    }

    public function getCustomer()
    {
        return Mage::getModel('customer/customer')->load($this->getCustomerId());
    }

    //the next logic is moved to Cminds_Rma_RmaController function formPostAction()
//    protected function _beforeSave()
//    {
//        if (!$this->getId()) {
//            $this->isObjectNew(true);
//
//            $this->setAutoincrementId($this->prepareIncrementId());
//            $this->setCustomerId(Mage::getSingleton('customer/session')->getCustomer()->getId());
//            $this->setCreatedAt(date('Y-m-d H:i:s'));
//            $this->setStatusId(self::DEFAULT_OPEN_ID);
//
//            if (!$this->getQty()) {
//                Mage::throwException("Products are missing");
//            }
//        }
//        Mage::dispatchEvent('model_save_before', array('object' => $this));
//        Mage::dispatchEvent($this->_eventPrefix.'_save_before', $this->_getEventData());
//        return $this;
//    }
//
//    protected function _afterSave()
//    {
//        $qty = $this->getQty();
//
//        if ($qty) {
//            foreach ($qty as $item_id => $value) {
//                $item = Mage::getModel('sales/order_item')->load($item_id);
//
//                if (!$item->getId()) {
//                    continue;
//                }
//
//                $rmaItem = Mage::getModel('cminds_rma/rma_item');
//                $rmaItem->setRmaId($this->getId());
//                $rmaItem->setItemId($item_id);
//                $rmaItem->setProductId($item->getProductId());
//                $rmaItem->setProductName($item->getName());
//                $rmaItem->setQty($value);
//                $rmaItem->setCreatedAt(date('Y-m-d H:i:s'));
//                $rmaItem->save();
//            }
//        }
//
//        if ($this->isObjectNew()) {
//            $this->notifyAdmin();
//            $this->notifyCustomer();
//        } else {
//            $this->notifyCustomerUpdate();
//        }
//
//        $this->cleanModelCache();
//        Mage::dispatchEvent('model_save_after', array('object'=>$this));
//        Mage::dispatchEvent($this->_eventPrefix.'_save_after', $this->_getEventData());
//        return $this;
//    }

//    protected function prepareIncrementId()
    public function prepareIncrementId()
    {
        $lastItem = Mage::getModel('cminds_rma/rma')->getCollection()->setOrder('created_at')->getFirstItem();
        $incrementId = '99999999';

        if ($lastItem) {
            $incrementId = $lastItem->getAutoincrementId();
        }
        return $incrementId + 1;
    }

//    protected function notifyCustomer()
    public function notifyCustomer()
    {
        $customer = $this->getCustomer();

        /**
         * @var Cminds_Rma_Helper_Data $dataHelper
         */
        $dataHelper = Mage::helper("cminds_rma");

        try {
            $emailTemplate  = Mage::getModel('core/email_template')
                ->loadDefault($dataHelper->getCustomerCreatedEmailTemplate());

            $emailTemplateVariables = array();
            $emailTemplateVariables['rma'] = $this;
            $emailTemplateVariables['order'] = $this->getOrder();

            $emailTemplate->setSenderName($dataHelper->getSenderName());
            $emailTemplate->setSenderEmail($dataHelper->getSenderName());

            if ($dataHelper->getCopyMethod() === "bcc") {
                $emailTemplate->addBcc($dataHelper->getCopyEmails());
            }

            $emailTemplate->getProcessedTemplate($emailTemplateVariables);
            $emailTemplate->send($customer->getEmail(), $customer->getName(), $emailTemplateVariables);
        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addError($e->getMessage());
        }
    }

//    protected function notifyCustomerUpdate()
    public function notifyCustomerUpdate()
    {
        $customer = $this->getCustomer();

        /**
         * @var Cminds_Rma_Helper_Data $dataHelper
         */
        $dataHelper = Mage::helper("cminds_rma");

        try {
            $emailTemplate  = Mage::getModel('core/email_template')
                ->loadDefault($dataHelper->getCustomerUpdateEmailTemplate());

            $emailTemplateVariables = array();
            $emailTemplateVariables['rma'] = $this;
            $emailTemplateVariables['order'] = $this->getOrder();
            $lastComment = $this
                ->getAllStatusHistory()
                ->getLastItem()
                ->getCommentBody();

            if (!$lastComment) {
                return $this;
            }

            $emailTemplateVariables['comment'] = $lastComment;

            $emailTemplate->setSenderName($dataHelper->getSenderName());
            $emailTemplate->setSenderEmail($dataHelper->getSenderName());

            if ($dataHelper->getCopyMethod() === "bcc") {
                $emailTemplate->addBcc($dataHelper->getCopyEmails());
            }

            $emailTemplate->getProcessedTemplate($emailTemplateVariables);
            $emailTemplate->send($customer->getEmail(), $customer->getName(), $emailTemplateVariables);
        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addError($e->getMessage());
        }
    }

//    protected function notifyAdmin()
    public function notifyAdmin()
    {
        try {
            $emailTemplate  = Mage::getModel('core/email_template')
                ->loadDefault('rma_created_admin');

            $emailTemplateVariables = array();
            $emailTemplateVariables['rma'] = $this;
            $emailTemplateVariables['order'] = $this->getOrder();


            /**
             * @var Cminds_Rma_Helper_Data $dataHelper
             */
            $dataHelper = Mage::helper("cminds_rma");

            $emailTemplate->setSenderName($dataHelper->getSenderName());
            $emailTemplate->setSenderEmail($dataHelper->getSenderName());
            $emailTemplate->getProcessedTemplate($emailTemplateVariables);

            $emailTemplate->send(
                Mage::getStoreConfig('trans_email/ident_general/email'),
                Mage::getStoreConfig('trans_email/ident_general/name'),
                $emailTemplateVariables
            );
        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addError($e->getMessage());
        }
    }
}
