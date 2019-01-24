<?php 
class Zozoconcepts_Hybrid_Model_System_Config_Source_Footer_Beforeblocks_Visibilty
{
	public function toOptionArray()
    {
		return array(
			array('value' => '01',	'label' => Mage::helper('hybrid')->__('Two columns')),
			array('value' => '02',	'label' => Mage::helper('hybrid')->__('Full Width')),
        );
    }
}