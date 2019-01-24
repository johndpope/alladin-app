<?php


class Mss_Mdashboard_Adminhtml_MdashboardController extends Mage_Adminhtml_Controller_Action {

    /**
     * init layout and set active for current menu
     *
     * @return Mss_Bannerslider_Adminhtml_BannersliderController
     */
    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('mdashboard/mdashboard')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Dashboard Manager'), Mage::helper('adminhtml')->__('Dashboard Manager'));
        return $this;
    }

    /**
     * index action
     */
    public function indexAction() {
        $this->_initAction()
                ->renderLayout();
    }

    /**
     * view and edit item action
     */
    public function editAction() {
        $id = $this->getRequest()->getParam('id');
        $store = $this->getRequest()->getParam('store');
        $model = Mage::getModel('mdashboard/mdashboard')->setStoreId($store)->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data))
                $model->setData($data);

            Mage::register('mdashboard_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('mdashboard/mdashboard');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('mdashboard/adminhtml_mdashboard_edit'))
                    ->_addLeft($this->getLayout()->createBlock('mdashboard/adminhtml_mdashboard_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mdashboard')->__($this->__('Item does not exist')));
            $this->_redirect('*/*/');
        }
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function addinAction() {
        $this->loadLayout();
        $this->_setActiveMenu('mdashboard/mdashboard');

        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

        $this->_addContent($this->getLayout()->createBlock('mdashboard/adminhtml_addbutton')->setTemplate('mdashboard/addbanner.phtml'));

        $this->renderLayout();
    }

    /**
     * save item action
     */
    public function saveAction() {        
        if ($data = $this->getRequest()->getParams()) {     
            $model = Mage::getModel('mdashboard/mdashboard');
            if (isset($data['image']['delete'])) {
                Mage::helper('mdashboard')->deleteImageFile($data['image']['value']);
            }  

            $image = Mage::helper('mdashboard')->uploadBannerImage();

            if ($image || (isset($data['image']['delete']) && $data['image']['delete'])) {
                $data['image'] = $image;
            } else {
                unset($data['image']);
            }

            $model->setData($data);
            try {

                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('mdashboard')->__('Dashboard was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                //Zend_debug::dump($this->getRequest()->getParam('slider'));die();
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($this->__($e->getMessage()));
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mdashboard')->__( $this->__('Unable to find Dashboard to save')));
        $this->_redirect('*/*/');
    }

    /**
     * delete item action
     */
    public function deleteAction() {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('mdashboard/mdashboard');
                $model->setId($this->getRequest()->getParam('id'))
                        ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Order was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError( $this->__($e->getMessage()));
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id'), 'store' => $this->getRequest()->getParam("store")));
            }
        }
        $this->_redirect('*/*/');
    }

    /**
     * mass delete item(s) action
     */
    public function massDeleteAction() {
        $bannersliderIds = $this->getRequest()->getParam('banner');
        if (!is_array($bannersliderIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__( $this->__('Please select item(s)')));
        } else {
            try {
                foreach ($bannersliderIds as $bannersliderId) {
                    $bannerslider = Mage::getModel('mdashboard/mdashboard')->load($bannersliderId);
                    $bannerslider->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Total of %d record(s) were successfully deleted', count($bannersliderIds)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError( $this->__($e->getMessage()));
            }
        }
        $this->_redirect('*/*/index', array('store' => $this->getRequest()->getParam("store")));
    }

    /**
     * mass change status for item(s) action
     */
    public function massStatusAction() {
        $bannerIds = $this->getRequest()->getParam('banner');
        if (!is_array($bannerIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($bannerIds as $bannerId) {
                    $banner = Mage::getSingleton('mdashboard/mdashboard')
                            ->load($bannerId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) were successfully updated', count($bannerIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError( $this->__($e->getMessage()));
            }
        }
        $this->_redirect('*/*/index', array('store' => $this->getRequest()->getParam("store")));
    }

 

    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('mdashboard');
    }
}
