<?php

class Zozoconcepts_Hybrid_Model_System_Config_Source_Design_Navshadow
{
    public function toOptionArray()
    {
        return array(
            array('value' => '',                     'label' => Mage::helper('hybrid')->__('None')),
			array('value' => 'inner-container',      'label' => Mage::helper('hybrid')->__('Inner container')),
			array('value' => 'bar',                  'label' => Mage::helper('hybrid')->__('Menu items')),
        );
    }
}