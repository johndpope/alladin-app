<?php
class Zozoconcepts_Hybrid_Model_System_Config_Source_Header_Cart_Block
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'offcanvas', 'label' => Mage::helper('hybrid')->__('Off Canvas type cart')),
            array('value' => 'drop', 'label' => Mage::helper('hybrid')->__('Dropdown Cart')),
        );
    }
}