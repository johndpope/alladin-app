<?php

class Zozoconcepts_Hybrid_Model_System_Config_Source_Design_Css_Background_Positionx
{
    public function toOptionArray()
    {
		return array(
			array('value' => 'left',	'label' => Mage::helper('hybrid')->__('left')),
            array('value' => 'center',	'label' => Mage::helper('hybrid')->__('center')),
            array('value' => 'right',	'label' => Mage::helper('hybrid')->__('right'))
        );
    }
}