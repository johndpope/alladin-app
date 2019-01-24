<?php
class Cminds_SupplierSubscriptions_Adminhtml_PlanController extends Mage_Adminhtml_Controller_Action
{
    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('admin/system/plans');
    }
    public function indexAction() {
        $this->_title($this->__('Subscription Plans'));
        $this->loadLayout();
        $this->_setActiveMenu('System');
        $this->_addContent($this->getLayout()->createBlock('suppliersubscriptions/adminhtml_plan_list'));
        $this->renderLayout();
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $plan_id = $this->getRequest()->getParam('id', null);
        $model = Mage::getModel('suppliersubscriptions/plan')->load($plan_id);

        if ($model) {
            Mage::register('plan_data', $model);
        }

        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('suppliersubscriptions/adminhtml_plan_edit'));
        $this->renderLayout();
    }

    public function saveAction() {
        if ($plan_id = $this->getRequest()->getParam('id', false)) {
            $plan = Mage::getModel('suppliersubscriptions/plan')
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
                    $plan = Mage::getModel('suppliersubscriptions/plan');
                    unset($postData['id']);
                    $postData['created_at'] = date('Y-m-d H:i:s');
                    $postData['updated_at'] = date('Y-m-d H:i:s');
                } else {
                    $postData['updated_at'] = date('Y-m-d H:i:s');
                }
                $plan->addData($postData);
                $plan->save();

                $planProduct = new Varien_Object();
                if(!isset($postData['id'])) {
                    $postData['id'] = $plan->getId();
                    $planProduct = $this->_createVirtualProduct($postData);
                } else {
                    $planProduct = $this->_updateVirtualProduct($postData);
                }

                if (!$planProduct->getId()) {
                    $this->_getSession()->addError($this->__('Can\'t create or update plan product.'));
                } else {
                    $plan->setProductId($planProduct->getId())
                        ->save();
                    $this->_getSession()->addSuccess($this->__('The subscription plan has been saved.'));
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
            $field = Mage::getModel('suppliersubscriptions/plan');
            $field->load($fieldId);

            if (!$field->getId()) {
                $this->_getSession()->addError(
                    $this->__('This plan no longer exists.')
                );
            }
            $planName = $field->getName();
            try {
                $planProduct = Mage::getModel('catalog/product')->load($field->getProductId());
                $field->delete();
                if ($planProduct->getId()) {
                    $planProduct->delete();
                }
            } catch (Exception $e) {
                $this->_getSession()->addError(
                    $this->__('Can not delete this plan.')
                );
            }
        }

        $this->_getSession()->addSuccess(
            $this->__('Plan "%s" have been deleted.', $planName)
        );
        return $this->_redirect(
            '*/*/index'
        );
    }

    /**
     * @param $postData
     * @return false|Mage_Core_Model_Abstract
     */
    private function _createVirtualProduct($postData)
    {
        $product = Mage::getModel('catalog/product');

        $websites = array();
        foreach (Mage::app()->getWebsites() as $website) {
            $websites[] = $website->getId();
        }

        $data = array(
            'attribute_set_id' => $product->getDefaultAttributeSetId(),
            'type_id' => Mage_Catalog_Model_Product_Type::TYPE_VIRTUAL,
            'sku' => Mage::helper('supplierfrontendproductuploader')->clearString('Subscription Plan ' . $postData['id']),
            'name' => 'Subscription Plan ' . $postData['name'],
            'description' => 'Subscription Virtual Product',
            'short_description' => 'Subscription Virtual Product',
            'status' => Mage_Catalog_Model_Product_Status::STATUS_ENABLED,
            'visibility' => Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE,
            'website_ids' => $websites,
            'price' => number_format($postData['price'], 2),
            'tax_class_id' => 0,
            'created_at' => strtotime('now')
        );

        $product->addData($data)
            ->setStockData(array(
                'use_config_manage_stock' => 0,
                'is_in_stock' => 1,
                'qty' => 9999,
                'manage_stock' => 0,
                'use_config_notify_stock_qty' => 0
            ))
            ->save();

        return $product;
    }

    /**
     * @param $postData
     * @return false|Mage_Catalog_Model_Product|Mage_Core_Model_Abstract
     * @throws Exception
     */
    private function _updateVirtualProduct($postData) {
        /* @var $product Mage_Catalog_Model_Product */
        $productId = Mage::getModel('catalog/product')->getIdBySku(Mage::helper('supplierfrontendproductuploader')->clearString('Subscription Plan ' . $postData['id']));
        if (!$productId) {
            return $this->_createVirtualProduct($postData);
        }
        $product = Mage::getModel('catalog/product')->load($productId);
        $product->setPrice(number_format($postData['price'], 2));
        $product->save();
        return $product;
    }
}
