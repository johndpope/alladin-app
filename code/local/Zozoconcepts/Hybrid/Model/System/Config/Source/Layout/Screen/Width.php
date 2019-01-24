<?php 
class Zozoconcepts_Hybrid_Model_System_Config_Source_Layout_Screen_Width
{
	public function toOptionArray()
    {
		return array(
			array('value' => 'wide',	 'label' => Mage::helper('hybrid')->__('Wide')),
			array('value' => 'boxed',	'label' => Mage::helper('hybrid')->__('Boxed')),
			);
    }
}