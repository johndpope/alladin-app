<?php
class Cminds_Rma_Adminhtml_Rma_StatusController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction() {
        $this->_title($this->__('Return Merchandise Authorization'));
        $this->loadLayout();
        $this->_setActiveMenu('rma');
        $this->_addContent($this->getLayout()->createBlock('cminds_rma/adminhtml_rma_status_list'));
        $this->renderLayout();
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $status = Mage::getModel('cminds_rma/rma_status');
        if ($statusId = $this->getRequest()->getParam('id', false)) {
            $status->load($statusId);

            if (!$status->getId()) {
                $this->_getSession()->addError(
                    $this->__('This status no longer exists.')
                );

                return $this->_redirect(
                    '*/*/list'
                );
            }
        }

        Mage::register('current_status', $status);

        $editBlock = $this->getLayout()->createBlock(
            'cminds_rma/adminhtml_rma_status_edit'
        );

        $this->loadLayout()
            ->_addContent($editBlock)
            ->renderLayout();

    }

    public function saveAction()
    {
        if ($postData = $this->getRequest()->getPost()) {
            try {
                $status = Mage::getModel('cminds_rma/rma_status')->load($postData['entity_id']);

                if(empty($postData['entity_id'])) {
                    unset($postData['entity_id']);
                }

                $status->addData($postData);
                $status->setData('created_at', date('Y-m-d H:i:s'));
                $status->save();

                $this->_getSession()->addSuccess(
                    $this->__('Status has been saved.')
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
