<?php 
class Zozoconcepts_Hybrid_Model_System_Config_Source_Footer_Beforeblocks_Featureditems_Theme
{
	public function toOptionArray()
    {
		return array(
			array('value' => 'lazyload',	'label' => Mage::helper('hybrid')->__('Lazyload')),
			array('value' => 'singleitem',	'label' => Mage::helper('hybrid')->__('Single Item')),
        );
    }
}