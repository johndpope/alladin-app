<?php

class Zozoconcepts_Hybrid_Model_System_Config_Source_Product_Zoom_Type
{
    public function toOptionArray()
    {
        return array(
            array('value' => '0', 'label' => Mage::helper('hybrid')->__('Inner Zoom')),
            array('value' => '1', 'label' => Mage::helper('hybrid')->__('Right Side Zoom'))
        );
    }
}