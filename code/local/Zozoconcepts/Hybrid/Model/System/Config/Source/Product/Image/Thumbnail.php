<?php

class Zozoconcepts_Hybrid_Model_System_Config_Source_Product_Image_Thumbnail
{
    public function toOptionArray()
    {
        return array(
            array('value' => '', 'label' => Mage::helper('hybrid')->__('Horizontal')),
            array('value' => 'vertical', 'label' => Mage::helper('hybrid')->__('Vertical'))
        );
    }
}