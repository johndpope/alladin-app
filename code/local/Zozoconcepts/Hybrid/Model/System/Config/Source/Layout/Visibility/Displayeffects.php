<?php 
class Zozoconcepts_Hybrid_Model_System_Config_Source_Layout_Visibility_Displayeffects
{
	public function toOptionArray()
    {
		return array(
			array('value' => 'disp',	'label' => Mage::helper('hybrid')->__('Display')),
			array('value' => 'dondisp',	'label' => Mage::helper('hybrid')->__('Do not Display')),
        );
    }
}