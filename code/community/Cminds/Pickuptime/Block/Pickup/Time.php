<?php
class Cminds_Pickuptime_Block_Pickup_Time extends Mage_Core_Block_Template
{
    private $_vendor = false;

    public function _getVendor()
    {
        if(!$this->_vendor) {
            $loggedUser = Mage::getSingleton( 'customer/session', array('name' => 'frontend') );
            $this->_vendor = Mage::getModel('customer/customer')->load($loggedUser->getCustomer()->getEntityId());
        }

        return $this->_vendor;
    }

    public function getCurrentVendorPickupTimes()
    {
        return Mage::getModel('cminds_pickuptime/pickuptime')->load($this->_getVendor()->getId(), 'vendor_id');
    }

    public function getCurrentVendorExcludedDates()
    {
        $excluded = Mage::getModel('cminds_pickuptime/excluded')->getCollection()->addFieldtoFilter('vendor_id', $this->_getVendor()->getId());
        return $excluded;
    }
}
