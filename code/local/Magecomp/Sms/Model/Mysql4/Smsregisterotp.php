<?php
class Magecomp_Sms_Model_Mysql4_Smsregisterotp extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('sms/smsregisterotp', 'id');
    }
}