<?php

class Magecomp_Sms_Model_Mysql4_Smsreport_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('sms/Smsreport');
    }
}