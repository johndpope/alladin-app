<?php
class Cminds_Pickuptime_Block_Selector extends Mage_Core_Block_Template {
    private $_vendor = false;
    private $_vendorPickupTimes = false;
    protected $_template = 'cminds_pickuptime/selector.phtml';
    public function _getVendor() {

        if(!$this->_vendor) {
            $product = Mage::registry('current_product');
            $this->_vendor = Mage::getModel('customer/customer')->load($product->getCreatorId());
        }

        return $this->_vendor;
    }

    public function getCurrentVendorPickupTimes() {
        if(!$this->_vendorPickupTimes) {
            $this->_vendorPickupTimes = Mage::getModel('cminds_pickuptime/pickuptime')->load($this->_getVendor()->getId(), 'vendor_id');
        }

        return $this->_vendorPickupTimes;
    }

    public function getCurrentVendorExcludedDates() {
        $excluded = Mage::getModel('cminds_pickuptime/excluded')->getCollection()->addFieldtoFilter('vendor_id', $this->_getVendor()->getId());
        return $excluded;
    }

    public function getInvalidDates() {
        $currentPickupTimes = $this->getCurrentVendorPickupTimes();
        $disabledDays = array();
        if($currentPickupTimes) {
            if($currentPickupTimes->getMondayTimeStart() == null && $currentPickupTimes->getMondayTimeEnd() == null ) {
                $disabledDays[] = 1;
            }
            if($currentPickupTimes->getTuesdayTimeStart() == null && $currentPickupTimes->getTuesdayTimeEnd() == null ) {
                $disabledDays[] = 2;
            }
            if($currentPickupTimes->getWednesdayTimeStart() == null && $currentPickupTimes->getWednesdayTimeEnd() == null ) {
                $disabledDays[] = 3;
            }
            if($currentPickupTimes->getThursdayTimeStart() == null && $currentPickupTimes->getThursdayTimeEnd() == null ) {
                $disabledDays[] = 4;
            }
            if($currentPickupTimes->getFridayTimeStart() == null && $currentPickupTimes->getFridayTimeEnd() == null ) {
                $disabledDays[] = 5;
            }
            if($currentPickupTimes->getSaturdayTimeStart() == null && $currentPickupTimes->getSaturdayTimeEnd() == null ) {
                $disabledDays[] = 6;
            }
            if($currentPickupTimes->getSundayTimeStart() == null && $currentPickupTimes->getSundayTimeEnd() == null ) {
                $disabledDays[] = 0;
            }
        }

        return json_encode($disabledDays);
    }

    public function getAvailableTimes() {
        $currentPickupTimes = $this->getCurrentVendorPickupTimes();
        $disabledDays = array();
        if($currentPickupTimes) {
            $disabledDays[] = array($currentPickupTimes->getMondayTimeStart(), $currentPickupTimes->getMondayTimeEnd());
            $disabledDays[] = array($currentPickupTimes->getTuesdayTimeStart(), $currentPickupTimes->getTuesdayTimeEnd());
            $disabledDays[] = array($currentPickupTimes->getWednesdayTimeStart(), $currentPickupTimes->getWednesdayTimeEnd());
            $disabledDays[] = array($currentPickupTimes->getThursdayTimeStart(), $currentPickupTimes->getThursdayTimeEnd());
            $disabledDays[] = array($currentPickupTimes->getFridayTimeStart(), $currentPickupTimes->getFridayTimeEnd());
            $disabledDays[] = array($currentPickupTimes->getSaturdayTimeStart(), $currentPickupTimes->getSaturdayTimeEnd());
            $disabledDays[] = array($currentPickupTimes->getSundayTimeStart(), $currentPickupTimes->getSundayTimeEnd());
        }

        return json_encode($disabledDays);
    }

    public function getExcludedDates() {
        $excludedDates = $this->getCurrentVendorExcludedDates();

        return $excludedDates->toJson();
    }

    public function getHoursAhead() {
        $currentPickupTimes = $this->getCurrentVendorPickupTimes();
        return (int) $currentPickupTimes->getDaysAhead();
    }
}
