<?php
class Cminds_SupplierSubscriptions_Adminhtml_PushmessagesController extends Mage_Adminhtml_Controller_Action
{
    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('admin/system/plans');
    }
    public function indexAction() {
        $this->_title($this->__('Subscription Plans'));
        $this->loadLayout();
        $this->_setActiveMenu('System');
        $this->_addContent($this->getLayout()->createBlock('suppliersubscriptions/adminhtml_pushmessages_list'));
        $this->renderLayout();
    }
    public function newAction() {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $plan_id = $this->getRequest()->getParam('id', null);
        $model = Mage::getModel('suppliersubscriptions/pushmessage')->load($plan_id);

        if ($model) {
            Mage::register('message', $model);
        }

        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('suppliersubscriptions/adminhtml_pushmessages_edit'));
        $this->renderLayout();
    }

    public function saveAction() {
        if ($plan_id = $this->getRequest()->getParam('id', false)) {
            $plan = Mage::getModel('suppliersubscriptions/pushmessage')
                ->load($plan_id);

            if (!$plan->getId()) {
                $this->_getSession()->addError(
                    $this->__('This plan no longer exists.')
                );

                return $this->_redirect('*/*/index');
            }
        } else {
            $plan = false;
        }

        if ($postData = $this->getRequest()->getPost()) {
            try {
                if(!$plan) {
                    $plan = Mage::getModel('suppliersubscriptions/pushmessage');
                    unset($postData['id']);
                    $postData['created_at'] = date('Y-m-d H:i:s');
                    $postData['updated_at'] = date('Y-m-d H:i:s');
                } else {
                    $postData['updated_at'] = date('Y-m-d H:i:s');
                }

                $plan->addData($postData);
                $plan->save();

                if (!$plan->getId()) {
                    $this->_getSession()->addError($this->__('Can\'t create or update push message.'));
                } else {
                    $this->_getSession()->addSuccess($this->__('The push message plan has been saved.'));
                }

                return $this->_redirect('*/*/index');
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage());
            }
        }
    }
}
