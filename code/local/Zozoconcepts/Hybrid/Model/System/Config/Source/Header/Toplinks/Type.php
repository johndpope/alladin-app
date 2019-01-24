<?php

class Zozoconcepts_Hybrid_Model_System_Config_Source_Header_Toplinks_Type
{
    public function toOptionArray()
    {
		return array(
			array('value' => 'icon',	'label' => Mage::helper('hybrid')->__('Prefix Icons')),
			array('value' => 'labels',	'label' => Mage::helper('hybrid')->__('Label')),
        );
    }
}