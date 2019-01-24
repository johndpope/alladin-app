<?php
class Cminds_SupplierSubscriptions_Adminhtml_BusinessController extends Mage_Adminhtml_Controller_Action
{
    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('admin/system/business');
    }
    public function indexAction() {
        $this->_title($this->__('Business Profile'));
        $this->loadLayout();
        $this->_setActiveMenu('suppliers');
        $this->_addContent($this->getLayout()->createBlock('suppliersubscriptions/adminhtml_business_list'));
        $this->renderLayout();
    }
    public function newAction() {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $business_id = $this->getRequest()->getParam('id', null);
        $model = Mage::getModel('suppliersubscriptions/business')->load($business_id);

        if ($model) {
            Mage::register('business_data', $model);
        }

        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('suppliersubscriptions/adminhtml_business_edit'));
        $this->renderLayout();
    }

    public function saveAction() {
        if ($business_id = $this->getRequest()->getParam('id', false)) {
            $business = Mage::getModel('suppliersubscriptions/business')
                ->load($business_id);

            if (!$business->getId()) {
                $this->_getSession()->addError(
                    $this->__('This business no longer exists.')
                );

                return $this->_redirect('*/*/index');
            }
        } else {
            $business = false;
        }

        if ($postData = $this->getRequest()->getPost()) {
            try {
                if(!$business) {
                    $business = Mage::getModel('suppliersubscriptions/business');
                    unset($postData['id']);
                    $postData['created_at'] = date('Y-m-d H:i:s');
                    $postData['updated_at'] = date('Y-m-d H:i:s');
                } else {
                    $postData['updated_at'] = date('Y-m-d H:i:s');
                }
                $business->addData($postData);
                $business->save();

                if (!$business->getId()) {
                    $this->_getSession()->addError($this->__('Can\'t create or update business.'));
                } else {
                    $this->_getSession()->addSuccess($this->__('The subscription business has been saved.'));
                }

                return $this->_redirect('*/*/index');
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage());
            }
        }
    }

    public function deleteAction()
    {
        if ($fieldId = $this->getRequest()->getParam('id', false)) {
            $field = Mage::getModel('suppliersubscriptions/business');
            $field->load($fieldId);

            if (!$field->getId()) {
                $this->_getSession()->addError(
                    $this->__('This business no longer exists.')
                );
            }

            try {
                $field->delete();
            } catch (Exception $e) {
                $this->_getSession()->addError(
                    $this->__('Can not delete this business.')
                );
            }
        }

        return $this->_redirect(
            '*/*/index'
        );
    }
}
