<?php 
class Zozoconcepts_Hybrid_Model_System_Config_Source_Footer_Beforeblocks_Slidecount
{
	public function toOptionArray()
    {
		return array(
			array('value' => '3',	'label' => Mage::helper('hybrid')->__('3 Items Per Slide')),
			array('value' => '4',	'label' => Mage::helper('hybrid')->__('4 Items Per Slide')),
        );
    }
}