<?php
class Cminds_Rma_Adminhtml_Rma_ReasonController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction() {
        $this->_title($this->__('Return Merchandise Authorization - Reasons'));
        $this->loadLayout();
        $this->_setActiveMenu('rma');
        $this->_addContent($this->getLayout()->createBlock('cminds_rma/adminhtml_rma_reason_list'));
        $this->renderLayout();
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $reason = Mage::getModel('cminds_rma/rma_reason');
        if ($reasonId = $this->getRequest()->getParam('id', false)) {
            $reason->load($reasonId);

            if (!$reason->getId()) {
                $this->_getSession()->addError(
                    $this->__('This reason no longer exists.')
                );

                return $this->_redirect(
                    '*/*/list'
                );
            }
        }

        Mage::register('current_reason', $reason);

        $editBlock = $this->getLayout()->createBlock(
            'cminds_rma/adminhtml_rma_reason_edit'
        );

        $this->loadLayout()
            ->_addContent($editBlock)
            ->renderLayout();

    }

    public function saveAction()
    {
        if ($postData = $this->getRequest()->getPost()) {
            try {
                $reason = Mage::getModel('cminds_rma/rma_reason')->load($postData['entity_id']);

                if(empty($postData['entity_id'])) {
                    unset($postData['entity_id']);
                }

                $reason->addData($postData);
                $reason->setData('created_at', date('Y-m-d H:i:s'));
                $reason->save();

                $this->_getSession()->addSuccess(
                    $this->__('Reason has been saved.')
                );

                return $this->_redirect(
                    '*/*/'
                );
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage());
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('cminds_rma')->__('No data found to save'));
        $this->_redirect('*/*/');
    }
}
