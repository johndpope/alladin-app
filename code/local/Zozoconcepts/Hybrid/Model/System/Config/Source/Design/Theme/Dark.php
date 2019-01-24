<?php

class Zozoconcepts_Hybrid_Model_System_Config_Source_Design_Theme_Dark
{
    public function toOptionArray()
    {
		return array(
			array('value' => 'light', 'label' => Mage::helper('hybrid')->__('Light (Default)')),
			array('value' => 'dark', 'label' => Mage::helper('hybrid')->__('Dark'))
        );
    }
}