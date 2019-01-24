<?php

class Zozoconcepts_Hybrid_Model_System_Config_Source_Design_Css_Background_Positiony
{
    public function toOptionArray()
    {
		return array(
			array('value' => 'top',		'label' => Mage::helper('hybrid')->__('top')),
            array('value' => 'center',	'label' => Mage::helper('hybrid')->__('center')),
            array('value' => 'bottom',	'label' => Mage::helper('hybrid')->__('bottom'))
        );
    }
}