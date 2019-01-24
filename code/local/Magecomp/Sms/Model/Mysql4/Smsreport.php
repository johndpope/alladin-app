<?php
class Magecomp_Sms_Model_Mysql4_Smsreport extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('sms/smsreport', 'id');
    }
}