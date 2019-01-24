<?php 
class Zozoconcepts_Hybrid_Model_System_Config_Source_Layout_Catalog_Catalogstyle
{
	public function toOptionArray()
    {
		return array(
			array('value' => 'default',	'label' => Mage::helper('hybrid')->__('Default')),
			array('value' => 'style_1',	'label' => Mage::helper('hybrid')->__('Style 1')),
			array('value' => 'style_2',	'label' => Mage::helper('hybrid')->__('Style 2')),
			array('value' => 'style_3',	'label' => Mage::helper('hybrid')->__('Style 3')),
			array('value' => 'style_4',	'label' => Mage::helper('hybrid')->__('Style 4')),
			array('value' => 'style_5',	'label' => Mage::helper('hybrid')->__('Style 5')),
        );
    }
}