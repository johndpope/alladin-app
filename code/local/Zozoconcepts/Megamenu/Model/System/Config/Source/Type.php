<?php

class Zozoconcepts_Megamenu_Model_System_Config_Source_Type
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'top',     'label' => Mage::helper('megamenu')->__('Top Menu - Both Static and Categories')),
			//array('value' => 'only-static',     'label' => Mage::helper('megamenu')->__('Top Menu - Static Links')),
			array('value' => 'side',  'label' => Mage::helper('megamenu')->__('Side Menu'))
        );
    }
}