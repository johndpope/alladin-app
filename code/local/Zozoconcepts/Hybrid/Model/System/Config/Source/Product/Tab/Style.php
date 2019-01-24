<?php

class Zozoconcepts_Hybrid_Model_System_Config_Source_Product_Tab_Style
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'horizontal', 'label' => Mage::helper('hybrid')->__('Horizontal')),
            array('value' => 'vertical', 'label' => Mage::helper('hybrid')->__('Vertical')),
            array('value' => 'accordion', 'label' => Mage::helper('hybrid')->__('Accordion'))
        );
    }
}