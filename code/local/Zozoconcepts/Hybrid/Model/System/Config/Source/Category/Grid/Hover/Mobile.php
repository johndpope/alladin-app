<?php

class Zozoconcepts_Hybrid_Model_System_Config_Source_Category_Grid_Hover_Mobile
{
    public function toOptionArray()
    {
		return array(
			array('value' => '',	'label' => Mage::helper('hybrid')->__('')),
			array('value' => 640,	'label' => Mage::helper('hybrid')->__('640 px')),
			array('value' => 480,	'label' => Mage::helper('hybrid')->__('480 px')),
			array('value' => 320,	'label' => Mage::helper('hybrid')->__('320 px')),
        );
    }
}