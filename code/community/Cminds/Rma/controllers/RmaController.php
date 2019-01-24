<?php

class Cminds_Rma_RmaController extends Mage_Core_Controller_Front_Action
{
   public function listAction()
   {
        if (!Mage::helper("cminds_rma")->isEnabled()) {
            $this->trigger404();
        }

        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('My Requests'));
        $this->renderLayout();
   }

    public function createAction()
    {
        if (!Mage::helper("cminds_rma")->isEnabled()) {
            $this->trigger404();
        }

        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Create Request'));
        $this->renderLayout();
    }

   public function getProductCollectionAction()
   {
       if (!Mage::helper("cminds_rma")->isEnabled()) {
           $this->trigger404();
       }

      $orderId = $this->getRequest()->getParam('order_id', null);

      if(!$orderId) {
         $this->trigger404();
         return;
      }

      Mage::register('marketplace_rma_order', $orderId);

      echo $this->getLayout()->createBlock('cminds_rma/rma_create_products')->toHtml();
   }

   public function viewAction()
   {
       if (!Mage::helper("cminds_rma")->isEnabled()) {
           $this->trigger404();
       }
      $rmaId = $this->getRequest()->getParam('rma_id', null);

      Mage::register('marketplace_rma', $rmaId);
      $rma = Mage::getModel('cminds_rma/rma')->load($rmaId);
      if($rma->getCustomerId() != Mage::getSingleton('customer/session')->getCustomer()->getId()) {
         $this->trigger404();
         return;
      }
      $this->loadLayout();
      $this->getLayout()->getBlock('head')->setTitle($this->__('RMA #%s - %s', $rma->getAutoincrementId(), $rma->getStatusLabel()));
      $this->renderLayout();
   }

    public function formPostAction()
    {
        if (!Mage::helper("cminds_rma")->isEnabled()) {
            $this->trigger404();
        }

        $postData = $this->getRequest()->getPost();

        if (!isset($postData['order_id'])) {
            $this->_forceError("No Order Selected");
        }

        $order = Mage::getModel('sales/order')->load($postData['order_id']);

        $dataRma = Mage::getModel('cminds_rma/rma');
        if (!$postData['id']) {
            $postData['autoincrement_id'] = $dataRma->prepareIncrementId();
            $postData['customer_id'] = Mage::getSingleton('customer/session')
                ->getCustomer()
                ->getId();
            $postData['created_at'] = date('Y-m-d H:i:s');
            $postData['status_id'] = Cminds_Rma_Model_Rma::DEFAULT_OPEN_ID;
        }

        if ($order->getCustomerId() != Mage::getSingleton('customer/session')->getCustomer()->getId()) {
            $this->trigger404();
            return;
        }

        if (!isset($postData['order_id'])) {
            $this->_forceError("No Order Selected");
        }

        try {
//            Mage::getModel("cminds_rma/rma")->setData($postData)->save();
            $dataRma->setData($postData)->save();

            if (!$postData['qty']) {
                Mage::throwException("Products are missing");
            } else {
                foreach ($postData['qty'] as $item_id => $value) {
                    $item = Mage::getModel('sales/order_item')->load($item_id);

                    if (!$item->getId()) {
                        continue;
                    }

                    $rmaItem = Mage::getModel('cminds_rma/rma_item');
                    $rmaItem->setRmaId($dataRma->getId());
                    $rmaItem->setItemId($item_id);
                    $rmaItem->setProductId($item->getProductId());
                    $rmaItem->setProductName($item->getName());
                    $rmaItem->setQty($value);
                    $rmaItem->setCreatedAt(date('Y-m-d H:i:s'));
                    $rmaItem->save();
                }

                if ($dataRma->isObjectNew()) {
                    $dataRma->notifyAdmin();
                    $dataRma->notifyCustomer();
                } else {
                    $dataRma->notifyCustomerUpdate();
                }

                $dataRma->cleanModelCache();
            }
            $this->getResponse()->setRedirect(Mage::getUrl("*/*/list"));
        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addError($e->getMessage());
        }
    }

   public function cancelAction()
   {
       if (!Mage::helper("cminds_rma")->isEnabled()) {
           $this->trigger404();
       }

      $params = $this->getRequest()->getParams();

      if(!isset($params['rma_id'])) $this->_forceError("No RMA Selected");

      $order = Mage::getModel('cminds_rma/rma')->load($params['rma_id']);

      if($order->getCustomerId() != Mage::getSingleton('customer/session')->getCustomer()->getId()) {
         $this->trigger404();
         return;
         }

      try {
         $rma = Mage::getModel("cminds_rma/rma")->load($params['rma_id']);
         $rma->setData("status_id", Cminds_Rma_Model_Rma::DEFAULT_CANCELED_ID);
         $rma->save();

         $this->getResponse()->setRedirect(Mage::getUrl("*/*/list"));
      } catch(Exception $e) {
         Mage::getSingleton('core/session')->addError($e->getMessage());
      }
   }

   protected function forceError($msg)
   {
      Mage::getSingleton('core/session')->addError($this->__($msg));
      $this->getResponse()->setRedirect(Mage::getUrl("*/*/create"));
      exit;
   }

   protected function trigger404()
   {
   		$this->getResponse()->setHeader('HTTP/1.1','404 Not Found');
		$this->getResponse()->setHeader('Status','404 File not found');
		$pageId = Mage::getStoreConfig('web/default/cms_no_route');
		if (!Mage::helper('cms/page')->renderPage($this, $pageId)) {
		    $this->_forward('defaultNoRoute');
		}
   }
}
