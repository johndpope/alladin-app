<?php

class Zozoconcepts_Hybrid_Model_System_Config_Source_Newsletter_Popup
{
    public function toOptionArray()
    {
        return array(
            array('value' => '0', 'label' => Mage::helper('hybrid')->__('Disable')),
            array('value' => '1', 'label' => Mage::helper('hybrid')->__('Enable on Only Homepage')),
            array('value' => '2', 'label' => Mage::helper('hybrid')->__('Enable on All Pages'))
        );
    }
}