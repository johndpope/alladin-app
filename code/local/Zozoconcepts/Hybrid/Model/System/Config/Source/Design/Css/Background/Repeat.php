<?php

class Zozoconcepts_Hybrid_Model_System_Config_Source_Design_Css_Background_Repeat
{
    public function toOptionArray()
    {
		return array(
			array('value' => 'no-repeat',	'label' => Mage::helper('hybrid')->__('no-repeat')),
            array('value' => 'repeat',		'label' => Mage::helper('hybrid')->__('repeat')),
            array('value' => 'repeat-x',	'label' => Mage::helper('hybrid')->__('repeat-x')),
			array('value' => 'repeat-y',	'label' => Mage::helper('hybrid')->__('repeat-y'))
        );
    }
}