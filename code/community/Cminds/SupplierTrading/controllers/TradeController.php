<?php

class Cminds_SupplierTrading_TradeController extends Cminds_Supplierfrontendproductuploader_Controller_Action
{

    public function preDispatch()
    {
        parent::preDispatch();
        $this->_getHelper()->validateModule();
        $hasAccess = $this->_getHelper()->hasAccess();

        if (!$hasAccess) {
            $this->getResponse()->setRedirect(Mage::helper('customer')->getLoginUrl());
        }
    }

    public function sendAction()
    {
        $product_id = $this->_request->getParam('product_id', null);

        $price = $this->_request->getParam('price', null);
        $customer_id = $this->_request->getParam('customer_id', null);
        $qty = $this->_request->getParam('qty', null);

        $product = Mage::getModel('catalog/product')->load($product_id);
        $model = Mage::getModel('suppliertrading/trades');
        $model->setProductId($product_id);
        $model->setCustomerId($customer_id);
        $model->setQty($qty);
        $model->setPrice($price);
        $model->setSupplierId($product->getData('creator_id'));
        $model->setCreatedOn(date("Y-m-d H:i:s"));
        $model->save();

        $supplier = Mage::getModel('customer/customer')->load($product->getData('creator_id'));

        Mage::helper('suppliertrading/email')->sendTradeMail($product, $model, $supplier);
    }

    public function acceptAction()
    {
        $trade_id = $this->_request->getParam('trade_id', null);
        $model = Mage::getModel('suppliertrading/trades')->load($trade_id);
        $product = Mage::getModel('catalog/product')->load($model->getProductId());
        if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
            Mage::getSingleton('core/session')->addError('Please log in and try again.');
            $this->_redirect('customer/account/login');
        } elseif ($product->getData('creator_id') != Mage::getSingleton('customer/session')->getCustomer()->getId()) {
            Mage::getSingleton('core/session')->addError('Permission denied.');
            $this->_redirect('/');
        } else {
            if ($model->status != 0) {
                Mage::getSingleton('core/session')->addError('Price was accepted / rejected earlier.');
                $this->_redirect('suppliertrading/trade/trades/');
            } else {
                $customer = Mage::getModel('customer/customer')->load($model->getCustomerId());

                $model->status = 2;
                $model->save();
                $this->unActiveAllCustomerQuotes($customer);
                $this->createQuote($customer, $product, $model);

                Mage::helper('suppliertrading/email')->sendAcceptTradeMail($product, $model, $customer);
                Mage::getSingleton('core/session')->addSuccess('Price was accepted.');
                $this->_redirect('suppliertrading/trade/trades/');
            }

        }
    }

    public function createQuote($customer, $product, $trade)
    {
        $store = Mage::app()->getStore();

        $quote = Mage::getModel('sales/quote')->setStoreId($store->getId());
        $params = array(
            'qty' => $trade->getQty(),
        );

        $quote->assignCustomer($customer);
        $quoteItem = $quote->addProduct($product, new Varien_Object($params));
        $quoteItem->setCustomPrice($trade->getPrice());

        $quoteItem->setOriginalCustomPrice($trade->getPrice());
        // Collect Totals & Save Quote
        $quote->collectTotals()->save();
    }

    public function unActiveAllCustomerQuotes($customer)
    {
        $collection = Mage::getResourceModel('sales/quote_collection');
        $collection->getSelect()->where('(is_active=1 and customer_id=' . $customer->getId() . ')');
        foreach ($collection as $col) {
            $quote = Mage::getModel('sales/quote')->load($col->getId());
            $quote->setIsActive(0);
            $quote->save();
        }
    }


    public function rejectAction()
    {
        $trade_id = $this->_request->getParam('trade_id', null);
        $model = Mage::getModel('suppliertrading/trades')->load($trade_id);
        $product = Mage::getModel('catalog/product')->load($model->getProductId());

        if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
            Mage::getSingleton('core/session')->addError('Please log in and try again.');
            $this->_redirect('customer/account/login');
        } elseif ($product->getData('creator_id') != Mage::getSingleton('customer/session')->getCustomer()->getId()) {
            Mage::getSingleton('core/session')->addError('Permission denied.');
            $this->_redirect('/');
        } else {
            if ($model->status != 0) {
                Mage::getSingleton('core/session')->addError('Price was accepted / rejected earlier.');
                $this->_redirect('/suppliertrading/trade/trades/');
            } else {
                $this->_renderBlocks(true);
            }

        }
    }

    public function rejectSaveAction()
    {
        $trade_id = $this->_request->getParam('trade_id', null);
        $note = $this->_request->getParam('note', '');
        $model = Mage::getModel('suppliertrading/trades')->load($trade_id);
        $product = Mage::getModel('catalog/product')->load($model->getProductId());

        if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
            Mage::getSingleton('core/session')->addError('Please log in and try again.');
            $this->_redirect('customer/account/login');
        } elseif ($product->getData('creator_id') != Mage::getSingleton('customer/session')->getCustomer()->getId()) {
            Mage::getSingleton('core/session')->addError('Permission denied.');
            $this->_redirect('/');
        } else {
            if ($model->status != 0) {
                Mage::getSingleton('core/session')->addError('Price was accepted / rejected earlier.');
                $this->_redirect('suppliertrading/trade/trades/');
            } else {
                $customer = Mage::getModel('customer/customer')->load($product->getData('creator_id'));

                $model->note = $note;
                $model->status = 1;
                $model->save();

                Mage::helper('suppliertrading/email')->sendRejectTradeMail($product, $model, $customer, $note);

                Mage::getSingleton('core/session')->addSuccess('Price was rejected.');
                $this->_redirect('suppliertrading/trade/trades/');
            }

        }
    }

    public function tradesAction()
    {
        $this->_renderBlocks(true);
    }
}