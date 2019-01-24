<?php
class Cminds_Rma_Adminhtml_Rma_TypeController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction() {
        $this->_title($this->__('Return Merchandise Authorization - Types'));
        $this->loadLayout();
        $this->_setActiveMenu('rma');
        $this->_addContent($this->getLayout()->createBlock('cminds_rma/adminhtml_rma_type_list'));
        $this->renderLayout();
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $type = Mage::getModel('cminds_rma/rma_type');
        if ($typeId = $this->getRequest()->getParam('id', false)) {
            $type->load($typeId);

            if (!$type->getId()) {
                $this->_getSession()->addError(
                    $this->__('This type no longer exists.')
                );

                return $this->_redirect(
                    '*/*/list'
                );
            }
        }

        Mage::register('current_type', $type);

        $editBlock = $this->getLayout()->createBlock(
            'cminds_rma/adminhtml_rma_type_edit'
        );

        $this->loadLayout()
            ->_addContent($editBlock)
            ->renderLayout();

    }

    public function saveAction()
    {
        if ($postData = $this->getRequest()->getPost()) {
            try {
                $type = Mage::getModel('cminds_rma/rma_type')->load($postData['entity_id']);

                if(empty($postData['entity_id'])) {
                    unset($postData['entity_id']);
                }

                $type->addData($postData);
                $type->setData('created_at', date('Y-m-d H:i:s'));
                $type->save();

                $this->_getSession()->addSuccess(
                    $this->__('Type has been saved.')
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
