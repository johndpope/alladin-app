<?php

class Cminds_Rma_Adminhtml_RmaController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->_title($this->__('Return Requests'));
        $this->loadLayout();
        $this->_setActiveMenu('rma');
        $this->_addContent($this->getLayout()->createBlock('cminds_rma/adminhtml_rma_list'));
        $this->renderLayout();
    }

    public function newAction()
    {
//        $this->_forward('edit');
        $newBlock = $this
            ->getLayout()
            ->createBlock('cminds_rma/adminhtml_rma_new');

        $this->loadLayout()
            ->_addContent($newBlock)
            ->_addLeft(
                $this
                    ->getLayout()
                    ->createBlock('cminds_rma/adminhtml_rma_new_tabs')
            )
            ->renderLayout();
    }

    public function continueAction()
    {
        $postData = $this->getRequest()->getPost();
        $rmaCollection = Mage::getModel('cminds_rma/rma')->getCollection();

        foreach ($rmaCollection as $itemRma) {
            if ($itemRma->getOrderId() == $postData['order_id']) {
                $responseRmaId = $itemRma->getEntityId();
                break;
            } else {
                $responseRmaId = false;
            }
        }

        if (!$responseRmaId) {
            $order = Mage::getModel('sales/order')
                ->load($postData['order_id']);

            $newRMA = Mage::getModel('cminds_rma/rma');
            $newRMA->isObjectNew(true);
            $newRMA->setAutoincrementId(
                Mage::getModel('cminds_rma/rma')
                    ->prepareIncrementId()
            );
            $newRMA->setCustomerId($order->getCustomerId());
            $newRMA->setOrderId($order->getEntityId());
            $newRMA->setCreatedAt(date('Y-m-d H:i:s'));
            $newRMA->setStatusId(1);
            $newRMA->setQty(true);
            $newRMA->save();

            // set orders products
            foreach ($order->getAllItems() as $items) {
                $item = Mage::getModel('sales/order_item')
                    ->load($items->getItemId());

                $rmaItem = Mage::getModel('cminds_rma/rma_item');
                $rmaItem->setRmaId($newRMA->getId());
                $rmaItem->setItemId($items->getItemId());
                $rmaItem->setProductId($item->getProductId());
                $rmaItem->setProductName($item->getName());
                $rmaItem->setQty($item->getQty());
                $rmaItem->setCreatedAt(date('Y-m-d H:i:s'));
                $rmaItem->save();
            }

            $newRmaCollection = Mage::getModel("cminds_rma/rma")->getCollection();
            foreach ($newRmaCollection as $itemRma) {
                if ($itemRma->getOrderId() == $postData['order_id']) {
                    $responseRmaId = $itemRma->getEntityId();
                    break;
                }
            }
        }

        if ($responseRmaId) {
            $responseURL = $this->_redirect(
                '*/*/edit', array(
                    'id' => $responseRmaId,
                )
            );
        } else {
            Mage::getSingleton('adminhtml/session')->addError('It is not possible to create new RMA');
            $responseURL = $this->_redirect('*/*/');
        }

        $this->getResponse()->setBody($responseURL);
    }

    public function editAction()
    {
        $rma = Mage::getModel('cminds_rma/rma');
        if ($rmaId = $this->getRequest()->getParam('id', false)) {
            $rma->load($rmaId);

            if (!$rma->getId()) {
                $this->_getSession()->addError(
                    $this->__('This request no longer exists.')
                );

                return $this->_redirect(
                    '*/*/list'
                );
            }
        }

        Mage::register('rma_data', $rma);

        $editBlock = $this->getLayout()->createBlock(
            'cminds_rma/adminhtml_rma_edit'
        );

        $this->loadLayout()
            ->_addContent($editBlock)
            ->_addLeft($this->getLayout()->createBlock('cminds_rma/adminhtml_rma_edit_tabs'))
            ->renderLayout();
    }

    /**
     * Edit product Qty in RMA.
     *
     */
    public function editQtyAction()
    {
        if ($postData = $this->getRequest()->getPost()) {
            $rmaItem = Mage::getModel('cminds_rma/rma_item');
            if ($rmaEntityId = $this->getRequest()->getParam('entityId', false)) {
                $rmaItem->load($rmaEntityId);

                if (!$rmaItem->getId()) {
                    $this->_getSession()->addError(
                        $this->__('This request no longer exists.')
                    );

                    return $this->_redirect(
                        '*/*/list'
                    );
                } else {
                    $product = Mage::getModel('sales/order_item')
                        ->load($rmaItem->getItemId());
                    $qtyOrdered = $product->getQtyOrdered();
                    $qtyRefunded = $product->getQtyRefunded();
                    $newQty = $this->getRequest()->getParam('newQty');

                    if ($qtyRefunded == $qtyOrdered) {
                        $this->_getSession()
                            ->addError(
                                Mage::helper('cminds_rma')
                                    ->__('No quantity of this product for RMA')
                            );
                    } elseif (!Mage::helper("cminds_rma")->isVerifyNewQty($newQty, $qtyOrdered)) {
                        $this->_getSession()
                            ->addError(
                                Mage::helper('cminds_rma')
                                    ->__('This number quantity be "Numeric" and smaller "Qty Ordered"')
                            );
                    } else {
                        if (($qtyRefunded + $newQty) > $qtyOrdered) {
                            $this->_getSession()
                                ->addError(
                                    Mage::helper('cminds_rma')
                                        ->__('This quantity should be less or or equal '
                                            . ($qtyOrdered - $qtyRefunded))
                                );
                        } else {
                            $rmaItem->setQty($newQty)->save();
                            $this->_getSession()
                                ->addSuccess(
                                    Mage::helper('cminds_rma')
                                        ->__('Qty RMA has been saved.')
                                );
                        }
                    }
                }
            }
        }
    }

    public function saveAction()
    {
        if ($postData = $this->getRequest()->getPost()) {
            try {
                $rma = Mage::getModel('cminds_rma/rma')->load($this->getRequest()->getParam('id'));

                if (!$rma->getId()) {
                    throw new Exception($this->__("Request does not exists !"));
                }

                $rma->setStatusId($postData['status_id']);
                $rma->setIsPackageOpened($postData['is_package_opened']);
                $rma->setReasonId($postData['reason_id']);
                $rma->setAdditionalInformation($postData['additional_information']);

                $rma->save();

                if ($postData['comment'] && $rma->getId()) {
                    $comment = Mage::getModel('cminds_rma/rma_comment');
                    $comment->setRmaId($rma->getId());
                    $comment->setCommentBody($postData['comment']);
                    $comment->save();
                }

                if ($postData['note'] && $rma->getId()) {
                    $comment = Mage::getModel('cminds_rma/rma_comment');
                    $comment->setRmaId($rma->getId());
                    $comment->setCommentBody($postData['note']);
                    $comment->setIsCustomerNotified((int)isset($postData['notify_customer']));
                    $comment->save();
                }

                $this->_getSession()->addSuccess(
                    $this->__('RMA has been saved.')
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

    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            try {
                $rma = Mage::getModel('cminds_rma/rma')->load($id);
                $savedRma = $rma;
                $rma->delete();

                $this->_getSession()->addSuccess(
                    $this->__('RMA #%s has been removed.', $savedRma->getId())
                );

                return $this->_redirect(
                    '*/*/'
                );
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage());
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('cminds_rma')->__('Missing ID'));
        $this->_redirect('*/*/');
    }

    public function creditmemoAction()
    {
        try {
            if (!$id = $this->getRequest()->getParam('id', null)) {
                throw new Exception("RMA with this ID");
            }
            $rma = Mage::getModel('cminds_rma/rma')->load($id);
            $order = $rma->getOrder();

            $rmaItems = $rma->getAllItems();

            $creditmemoData = array(
                'qtys' => array(),
                'shipping_amount' => null,
                'adjustment_positive' => '0',
                'adjustment_negative' => null);

            foreach ($rmaItems as $item) {
                $creditmemoData['qtys'][$item->getItemId()] = $item->getQty();
            }

            $comment = 'Comment for Credit Memo';

            $notifyCustomer = true;
            $includeComment = false;
            $refundToStoreCreditAmount = '1';

            if ($order->getId() && $order->canCreditmemo()) {
                $service = Mage::getModel('sales/service_order', $order);
                $data = isset($data) ? $data : array();

                $creditmemo = $service->prepareCreditmemo($creditmemoData);
                if ($refundToStoreCreditAmount) {
                    if ($order->getCustomerIsGuest()) {
                    }
                    $refundToStoreCreditAmount = max(
                        0,
                        min($creditmemo->getBaseCustomerBalanceReturnMax(), $refundToStoreCreditAmount)
                    );
                    if ($refundToStoreCreditAmount) {
                        $refundToStoreCreditAmount = $creditmemo->getStore()->roundPrice($refundToStoreCreditAmount);
                        $creditmemo->setBaseCustomerBalanceTotalRefunded($refundToStoreCreditAmount);
                        $refundToStoreCreditAmount = $creditmemo->getStore()->roundPrice(
                            $refundToStoreCreditAmount * $order->getStoreToOrderRate()
                        );
                        $creditmemo->setBsCustomerBalTotalRefunded($refundToStoreCreditAmount);
                        $creditmemo->setCustomerBalanceRefundFlag(true);
                    }
                }
                $creditmemo->setPaymentRefundDisallowed(true)->register();

                if (!empty($comment)) {
                    $creditmemo->addComment($comment, $notifyCustomer);
                }

                Mage::getModel('core/resource_transaction')
                    ->addObject($creditmemo)
                    ->addObject($order)
                    ->save();
                $creditmemo->sendEmail($notifyCustomer, ($includeComment ? $comment : ''));
                $rma->close();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('cminds_rma')->__('Credit Memo has been created')
                );

                Mage::dispatchEvent('rma_success_credit_memo',
                    array('order_id' => $order->getId())
                );
            } else {
                MAge::throwException($this->__("Credit Memo cannot be created"));
            }

            return $this->_redirect(
                '*/*/index'
            );
        } catch (Exception $e) {
            Mage::logException($e);
            $this->_getSession()->addError($e->getMessage());
        }

        return $this->_redirect(
            '*/*/'
        );
    }
}
