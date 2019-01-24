<?php
class Mss_Mdashboard_Model_Mysql4_Mdashboard extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("mdashboard/mdashboard", "id");
    }
}