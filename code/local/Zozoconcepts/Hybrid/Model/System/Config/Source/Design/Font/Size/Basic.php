<?php

class Zozoconcepts_Hybrid_Model_System_Config_Source_Design_Font_Size_Basic
{
    public function toOptionArray()
    {
		return array(
            array('value' => '0',    'label' => Mage::helper('hybrid')->__('Default')),
            array('value' => '10px',    'label' => Mage::helper('hybrid')->__('10 px')),
            array('value' => '11px',    'label' => Mage::helper('hybrid')->__('11 px')),
			array('value' => '12px',	'label' => Mage::helper('hybrid')->__('12 px')),
			array('value' => '13px',	'label' => Mage::helper('hybrid')->__('13 px')),
            array('value' => '14px',    'label' => Mage::helper('hybrid')->__('14 px')),
            array('value' => '15px',    'label' => Mage::helper('hybrid')->__('15 px')),
            array('value' => '16px',    'label' => Mage::helper('hybrid')->__('16 px')),
            array('value' => '17px',    'label' => Mage::helper('hybrid')->__('17 px')),
            array('value' => '18px',	'label' => Mage::helper('hybrid')->__('18 px'))
        );
    }
}