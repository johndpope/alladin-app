<?php

class Zozoconcepts_Hybrid_Model_System_Config_Source_Design_Homestaticcolcount
{
    public function toOptionArray()
    {
        return array(
            array('value' => '1',                     'label' => Mage::helper('hybrid')->__('One')),
			array('value' => '2',      'label' => Mage::helper('hybrid')->__('Two')),
			array('value' => '3',                  'label' => Mage::helper('hybrid')->__('Three')),
        );
    }
}