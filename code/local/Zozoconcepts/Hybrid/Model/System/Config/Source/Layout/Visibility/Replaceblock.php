<?php 
class Zozoconcepts_Hybrid_Model_System_Config_Source_Layout_Visibility_Replaceblock
{
	public function toOptionArray()
    {
		return array(
			array('value' => 0, 'label' => Mage::helper('hybrid')->__('Disable Completely')),
            array('value' => 1, 'label' => Mage::helper('hybrid')->__('Don\'t Replace With Static Block')),
            array('value' => 2, 'label' => Mage::helper('hybrid')->__('If Empty, Replace With Static Block')),
			array('value' => 3, 'label' => Mage::helper('hybrid')->__('Replace With Static Block'))
        );
    }
}