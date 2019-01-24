<?php

class Zozoconcepts_Hybrid_Model_System_Config_Source_Design_Css_Background_Attachment
{
    public function toOptionArray()
    {
		return array(
			array('value' => 'fixed',	'label' => Mage::helper('hybrid')->__('fixed')),
            array('value' => 'scroll',	'label' => Mage::helper('hybrid')->__('scroll'))
        );
    }
}