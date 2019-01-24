<?php 
class Zozoconcepts_Hybrid_Model_System_Config_Source_Product_Related_Position
{
	public function toOptionArray()
    {
		return array(
			array('value' => '15',	'label' => Mage::helper('hybrid')->__('Top of the Secondary Column (below brand logo)')),
			array('value' => '20',	'label' => Mage::helper('hybrid')->__('Bottom of the Secondary Column')),
			array('value' => '25',	'label' => Mage::helper('hybrid')->__('At the side of the tabs')),
        );
    }
}