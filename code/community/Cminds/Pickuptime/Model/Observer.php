<?php

class Cminds_Pickuptime_Model_Observer extends Mage_Core_Model_Abstract
{
    private $_vendorPickupTimes;
    private $_vendor;

    public function navLoad($observer)
    {
        $event = $observer->getEvent();
        $items = $event->getItems();
        if (Mage::helper('cminds_pickuptime')->isEnabled()) {
            $items['PICKUP_TIME'] =  [
                'label'     => 'Pickup Time',
                'url' => 'supplier/pickup/time',
                'parent'    => 'SETTINGS',
                'action_names' => [
                    'cminds_supplierfrontendproductuploader_pickup_time',
                ],
                'sort'     => 5
            ];
        }
        $observer->getEvent()->setItems($items);
    }

    public function onItemAddedToCart($observer)
    {
        if (!Mage::helper('cminds_pickuptime')->isEnabled()) {
            return false;
        }

        if (!$this->canApply()) {
            return false;
        }

        $request = Mage::app()->getRequest();
        $date = false;

        foreach ($request->getPost('options') as $item) {
            if (array_key_exists('month', $item)) {
                $date = new DateTime();
                $date->setDate($item['year'], $item['month'], $item['day']);
                $h = $item['hour'];

                if (strtolower($item['day_part']) == 'pm') {
                    $h = $item['hour'] + 12;
                }

                $date->setTime($h, $item['minute'], 0);
            }
        }

        if ($date) {
            $quoteItem = $observer->getQuoteItem();
            $quoteItem->setPickupDate($date->format('Y-m-d H:i:s'));
        }
    }

    public function onConvertQuoteItemToOrderItem($observer)
    {
        if (!Mage::helper('cminds_pickuptime')->isEnabled()) {
            return false;
        }

        $quoteItem = $observer->getItem();
        $orderItem = $observer->getOrderItem();
        $orderItem->setPickupDate($quoteItem->getPickupDate());
    }

    private function _redirectToProductPage($message)
    {
        $product = Mage::getModel('catalog/product')
            ->load(Mage::app()->getRequest()->getParam('product', 0));

        Mage::getSingleton('checkout/session')->addError('Cannot add product to cart! ' . $message);
        Mage::app()->getFrontController()->getResponse()->setRedirect($product->getProductUrl())->sendResponse();
        exit();
    }

    function isDateExcluded($excludedDates, $date)
    {
        for ($i=0; $i < count($excludedDates); $i++) {
            if (!isset($excludedDates['items'][$i])) {
                continue;
            }
            $excludedDate = $excludedDates['items'][$i];
            if ($date->format("Y-m-d") == $excludedDate['date']) {
                return $excludedDates[$i];
            }
        }

        return false;
    }

    public function onItemBeforeAddToCart()
    {
        if (!$this->_getVendor()) {
            return false;
        }

        if (!$this->canApply()) {
            return false;
        }

        $disabledDates = $this->getInvalidDates();
        $excludedDates = $this->getExcludedDates();
        $availableTimes = $this->getAvailableTimes();

        foreach (Mage::app()->getRequest()->getParam('options', array()) as $item) {
            if (is_array($item) && array_key_exists('month', $item)) {
                $date = new DateTime();
                $date->setDate($item['year'], $item['month'], $item['day']);
                $h = $item['hour'];

                if (strtolower($item['day_part']) == 'pm') {
                    $h = $item['hour'] + 12;
                }

                $date->setTime($h, $item['minute'], 0);
                break;
            }
        }

        if (!isset($date) || !$date) {
            $this->_redirectToProductPage('Pickup Date is null.');
        }

        if (in_array($date->format('N'), $disabledDates)) {
            $this->_redirectToProductPage('Pickup Date is disabled.');
        }

        $disabledOptions = $this->isDateExcluded($excludedDates, $date);

        if ($disabledOptions) {
            if ($disabledOptions['start_date'] == '00:00:00' && $disabledOptions['end_date'] == '23:59:59') {
                $this->_redirectToProductPage('Pickup Date is excluded.');
            }
        }

        if (!isset($availableTimes[$date->format('N')])) {
            $this->_redirectToProductPage('Pickup Time is null.');
        }

        $currentTimeAvailability = $availableTimes[$date->format('N')];
        $minTime = $currentTimeAvailability[0];
        $maxTime = $currentTimeAvailability[1];
        $explodedMinTime = explode(":", $minTime);
        $explodedMaxTime = explode(":", $maxTime);

        $currentTime = clone $date;
        $currentTime->setTime($explodedMinTime[0], $explodedMinTime[1], $explodedMinTime[2]);
        $lastTime = clone $date;
        $lastTime->setTime($explodedMaxTime[0], $explodedMaxTime[1], $explodedMaxTime[2]);

        $now = new DateTime();

        if ($now > $date) {
            $this->_redirectToProductPage('Pickup Date is expired.');
        }

        if ($disabledOptions) {
            $disabledTimeStart = new DateTime();
            $disabledDateTimeOptions = explode(':', $disabledOptions['start_date']);
            $disabledTimeStart->setTime(
                $disabledDateTimeOptions[0],
                $disabledDateTimeOptions[1],
                $disabledDateTimeOptions[2]
            );
            $disabledTimeEnd = new DateTime();
            $disabledTimeEndOptions = explode(':', $disabledOptions['end_date']);
            $disabledTimeEnd->setTime(
                $disabledTimeEndOptions[0],
                $disabledTimeEndOptions[1],
                $disabledTimeEndOptions[2]
            );

            if ($currentTime > $disabledTimeStart) {
                $minTime = $disabledOptions['start_date'];
            }

            if ($lastTime > $disabledTimeEnd) {
                $maxTime = $disabledOptions['end_date'];
            }

            $timeAhead = $this->getHoursAhead();
            $disabledTimeEnd->modify("+$timeAhead hours");

            if ($date < $disabledTimeEnd) {
                $this->_redirectToProductPage('Pickup Time is disabled.');
            }
        }
    }

    private function canApply()
    {
        $product = Mage::getModel('catalog/product')->load(Mage::app()->getRequest()->getParam('product', 0));
        if (!$product->getOptions()) {
            return false;
        }
        foreach ($product->getOptions() as $option) {
            if ($option->getType() == "date_time" && $option->getSku() == "pickup") {
                return true;
            }
        }
        return false;
    }

    private function _getVendor()
    {
        $product = Mage::getModel('catalog/product')
            ->load(Mage::app()->getRequest()->getParam('product', 0));

        if (!$product->getId()) {
            $this->_redirectToProductPage();
            exit;
        }

        if (!$this->_vendor) {
            $this->_vendor = Mage::getModel('customer/customer')->load($product->getCreatorId());
        }

        if ($this->_vendor->getId()) {
            return $this->_vendor;
        }

        return false;
    }

    private function getCurrentVendorPickupTimes()
    {
        if (!$this->_vendorPickupTimes) {
            $this->_vendorPickupTimes = Mage::getModel('cminds_pickuptime/pickuptime')->load(
                $this->_getVendor()->getId(),
                'vendor_id'
            );
        }

        return $this->_vendorPickupTimes;
    }

    private function getCurrentVendorExcludedDates()
    {
        $excluded = Mage::getModel('cminds_pickuptime/excluded')
            ->getCollection()
            ->addFieldtoFilter('vendor_id', $this->_getVendor()->getId());
        return $excluded;
    }

    private function getInvalidDates()
    {
        $currentPickupTimes = $this->getCurrentVendorPickupTimes();
        $disabledDays = array();

        if ($currentPickupTimes) {
            if ($currentPickupTimes->getMondayTimeStart() == null && $currentPickupTimes->getMondayTimeEnd() == null) {
                $disabledDays[] = 1;
            }

            if ($currentPickupTimes->getTuesdayTimeStart() == null && $currentPickupTimes->getTuesdayTimeEnd() == null) {
                $disabledDays[] = 2;
            }

            if ($currentPickupTimes->getWednesdayTimeStart() == null && $currentPickupTimes->getWednesdayTimeEnd() == null) {
                $disabledDays[] = 3;
            }

            if ($currentPickupTimes->getThursdayTimeStart() == null && $currentPickupTimes->getThursdayTimeEnd() == null) {
                $disabledDays[] = 4;
            }

            if ($currentPickupTimes->getFridayTimeStart() == null && $currentPickupTimes->getFridayTimeEnd() == null) {
                $disabledDays[] = 5;
            }

            if ($currentPickupTimes->getSaturdayTimeStart() == null && $currentPickupTimes->getSaturdayTimeEnd() == null) {
                $disabledDays[] = 6;
            }

            if ($currentPickupTimes->getSundayTimeStart() == null && $currentPickupTimes->getSundayTimeEnd() == null) {
                $disabledDays[] = 0;
            }
        }

        return $disabledDays;
    }

    private function getAvailableTimes()
    {
        $currentPickupTimes = $this->getCurrentVendorPickupTimes();
        $disabledDays = array();

        if ($currentPickupTimes) {
            $disabledDays[] = array($currentPickupTimes->getMondayTimeStart(), $currentPickupTimes->getMondayTimeEnd());
            $disabledDays[] = array($currentPickupTimes->getTuesdayTimeStart(), $currentPickupTimes->getTuesdayTimeEnd());
            $disabledDays[] = array($currentPickupTimes->getWednesdayTimeStart(), $currentPickupTimes->getWednesdayTimeEnd());
            $disabledDays[] = array($currentPickupTimes->getThursdayTimeStart(), $currentPickupTimes->getThursdayTimeEnd());
            $disabledDays[] = array($currentPickupTimes->getFridayTimeStart(), $currentPickupTimes->getFridayTimeEnd());
            $disabledDays[] = array($currentPickupTimes->getSaturdayTimeStart(), $currentPickupTimes->getSaturdayTimeEnd());
            $disabledDays[] = array($currentPickupTimes->getSundayTimeStart(), $currentPickupTimes->getSundayTimeEnd());
        }

        return $disabledDays;
    }

    private function getExcludedDates()
    {
        $excludedDates = $this->getCurrentVendorExcludedDates();

        return $excludedDates->toArray();
    }

    private function getHoursAhead()
    {
        $currentPickupTimes = $this->getCurrentVendorPickupTimes();
        return (int) $currentPickupTimes->getDaysAhead();
    }

    public function onProductSaveAfter($observer)
    {
        if (!Mage::helper('cminds_pickuptime')->isEnabled()) {
            return false;
        }

        $product = $observer->getProduct();

        if (!$product->getHasOptions()) {
            $option = array(
                'title' => 'Pickup Date & Time',
                'type' => 'date_time',
                'is_require' => 1,
                'sort_order' => 0,
                'is_delete' => '',
                'previous_type' => '',
                'previous_group' => '',
                'price' => '0.00',
                'price_type' => 'fixed',
                'sku' => 'pickup'
            );
            $product->setCanSaveCustomOptions(true);
            $product->getOptionInstance()->addOption($option);
            $product->setHasOptions(true);
            $product->getResource()->save($product);
        }
    }
}