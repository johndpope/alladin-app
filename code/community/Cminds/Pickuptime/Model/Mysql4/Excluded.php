<?php
class Cminds_Pickuptime_Model_Mysql4_Excluded extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('cminds_pickuptime/vendor_pickup_time_excluded_days', 'id');
    }
}
