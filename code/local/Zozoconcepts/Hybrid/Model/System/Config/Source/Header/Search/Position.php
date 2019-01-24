<?php

class Zozoconcepts_Hybrid_Model_System_Config_Source_Header_Search_Position
{
    public function toOptionArray()
    {
		return array(
			array('value' => 'b',	'label' => Mage::helper('hybrid')->__('Before Cart')),
			array('value' => 'c',	'label' => Mage::helper('hybrid')->__('Before Compare')),
			array('value' => 'd',	'label' => Mage::helper('hybrid')->__('Before Top Links')),
			array('value' => 'false',	'label' => Mage::helper('hybrid')->__('Remove Search')),
        );
    }
}