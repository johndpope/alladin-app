<?php

class Cminds_OrderSplit_Model_Checkout_Type_Onepage
    extends Mage_Checkout_Model_Type_Onepage
{

    /**
     * @inheritdoc
     */
    public function saveOrder()
    {
        /**
         * @var Cminds_OrderSplit_Helper_Data $dataHelper
         */
        $dataHelper = Mage::helper("ordersplit");

        if (!$dataHelper->isEnabled()) {
            parent::saveOrder();
            return $this;
        }

        $this->validate();
        $isNewCustomer = false;
        $tempSplitItemsByVendors = array();

        switch ($this->getCheckoutMethod()) {
            case self::METHOD_GUEST:
                $this->_prepareGuestQuote();
                break;
            case self::METHOD_REGISTER:
                $this->_prepareNewCustomerQuote();
                $isNewCustomer = true;
                break;
            default:
                $this->_prepareCustomerQuote();
                break;
        }

        $quote = $this->getQuote();
        $totalItemsCount = 0;
        $vendorItemsQtyCount = array();
        $shippingBaseAmount = $quote->getShippingAddress()->getBaseShippingAmount();
        $shippingAmount = $quote->getShippingAddress()->getShippingAmount();

        foreach ($quote->getAllItems() as $item) {
            if ($item->getParentItemId()) {
                continue;
            }

            $isVirtual = false;

            if (in_array($item->getProduct()->getTypeId(), $this->getVirtualProductTypes())) {
                $isVirtual = true;
            }


            $product = Mage::getModel('catalog/product')->load(
                $item->getProduct()->getId()
            );

            $vendor_id = $product->getCreatorId();

            $tempSplitItemsByVendors[(int)$vendor_id][] = array(
                'product_id' => $item->getProduct()->getId(),
                'qty' => $item->getQty(),
                'super_attribute' => $item->getBuyRequest()->getSuperAttribute(),
                'options' => $item->getBuyRequest()->getOptions(),
            );

            $quote->removeItem($item->getId());
            $quote->setSubtotal(0);
            $quote->setBaseSubtotal(0);

            $quote->setSubtotalWithDiscount(0);
            $quote->setBaseSubtotalWithDiscount(0);

            $quote->setGrandTotal(0);
            $quote->setBaseGrandTotal(0);

            $quote->setTotalsCollectedFlag(false);
            $quote->collectTotals();

            if (!isset($vendorItemsQtyCount[$vendor_id])) {
                $vendorItemsQtyCount[$vendor_id] = 0;
            }

            if (!$isVirtual) {
                $vendorItemsQtyCount[$vendor_id] = $vendorItemsQtyCount[$vendor_id] + $item->getQty();
                $totalItemsCount                   = $totalItemsCount + $item->getQty();
            }
        }
        $unitShippingAmount = $shippingAmount / $totalItemsCount;
        $unitBaseShippingAmount = $shippingBaseAmount / $totalItemsCount;
        $quote->save();

        Mage::register("refresh-cache", true);
        $customerInvolved = false;

        foreach ($tempSplitItemsByVendors as $vendor_id => $vendorItems) {
            foreach ($vendorItems as $item) {
                $splitQuote = $quote;

                $productModel = Mage::getModel('catalog/product')
                    ->load($item['product_id']);

                $splitQuote
                    ->addProduct($productModel, new Varien_Object($item));
                $splitQuote
                    ->save();
            }

            $vendorItemsQty = isset($vendorItemsQtyCount[$vendor_id]) ? $vendorItemsQtyCount[$vendor_id] : 1;

            $splitQuote
                ->setTotalsCollectedFlag(false);

            $splitQuote
                ->getShippingAddress()
                ->unsetData('cached_items_all');

            $splitQuote
                ->getShippingAddress()
                ->unsetData('cached_items_nominal');

            $splitQuote
                ->getShippingAddress()
                ->unsetData('cached_items_nonnominal');

            $splitQuote
                ->getShippingAddress()
                ->setShippingAmount($unitShippingAmount * $vendorItemsQty)
                ->setShippingBaseAmount($unitBaseShippingAmount * $vendorItemsQty);

            $splitQuote
                ->getShippingAddress()
                ->collectTotals();

            $splitQuote
                ->collectTotals();

            $baseGrandTotal = $splitQuote->getBaseGrandTotal();
            $grandTotal = $splitQuote->getGrandTotal();

            $totalShipping = $splitQuote->getShippingAddress()->getShippingAmount();
            $baseTotalShipping = $splitQuote->getShippingAddress()->getBaseShippingAmount();

            $baseShippingAmount = $unitBaseShippingAmount * $vendorItemsQty;
            $shippingAmount = $unitShippingAmount * $vendorItemsQty;

            /*
             * Update shipping amount and totals for order
             * */

            $splitQuote
                ->getShippingAddress()
                ->setShippingAmount($shippingAmount)
                ->setBaseShippingAmount($baseShippingAmount)
                ->setBaseGrandTotal(($baseGrandTotal - $baseTotalShipping) + $baseShippingAmount)
                ->setGrandTotal(($grandTotal - $totalShipping) + $shippingAmount);

            $splitQuote->save();
            if ($isNewCustomer) {
                $splitQuote->setCustomerId(true);
            }

            $service = Mage::getModel('sales/service_quote', $splitQuote);
            $service->submitAll();

            if ($isNewCustomer && $customerInvolved === false) {
                try {
                    $this->_involveNewCustomer();
                    $customerInvolved = true;
                } catch (Exception $e) {
                    Mage::logException($e);
                }
            }

            $this->_checkoutSession->setLastQuoteId($splitQuote->getId())
                ->setLastSuccessQuoteId($splitQuote->getId())
                ->clearHelperData();

            $order = $service->getOrder();
            if ($order) {
                Mage::dispatchEvent(
                    'checkout_type_onepage_save_order_after',
                    array (
                        'order' => $order,
                        'quote' => $splitQuote
                    )
                );
                $splitQuote->removeAllItems();
                $splitQuote->setTotalsCollectedFlag(false);
                $splitQuote->collectTotals();


            }

            /**
             * a flag to set that there will be redirect to third party after confirmation
             * eg: paypal standard ipn
             */
            $redirectUrl = $this->getQuote()->getPayment()->getOrderPlaceRedirectUrl();
            /**
             * we only want to send to customer about new order when there is no redirect to third party
             */
            if (!$redirectUrl && $order->getCanSendNewEmailFlag()) {
                try {
                    $order->sendNewOrderEmail();
                } catch (Exception $e) {
                    Mage::logException($e);
                }
            }

            $this->_checkoutSession->setLastOrderId($order->getId())
                ->setRedirectUrl($redirectUrl)
                ->setLastRealOrderId($order->getIncrementId());

            $agreement = $order->getPayment()->getBillingAgreement();
            if ($agreement) {
                $this->_checkoutSession->setLastBillingAgreementId($agreement->getId());
            }
        }

        // add recurring profiles information to the session
        $profiles = $service->getRecurringPaymentProfiles();
        if ($profiles) {
            $ids = array();
            foreach ($profiles as $profile) {
                $ids[] = $profile->getId();
            }
            $this->_checkoutSession->setLastRecurringProfileIds($ids);
        }

        Mage::dispatchEvent(
            'checkout_submit_all_after',
            array( 'order'              => $order,
                'quote'              => $this->getQuote(),
                'recurring_profiles' => $profiles
            )
        );

        return $this;
    }

    /**
     * Retrieve array of virtual product types
     * @return array
     */
    protected function getVirtualProductTypes()
    {
        return array(
            "grouped",
            "virtual",
            "downloadable",
            "giftcard"
        );
    }
}