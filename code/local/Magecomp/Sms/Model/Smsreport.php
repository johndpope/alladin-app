<?php
class Magecomp_Sms_Model_Smsreport extends Mage_Core_Model_Abstract
{
    public function _construct()
    {    
		parent::_construct();
        $this->_init('sms/smsreport');
    }
}