<?php

class Zozoconcepts_Hybrid_Model_System_Config_Source_Design_Font_Google_Subset
{
	public function toOptionArray()
	{
		return array(
			array('value' => 'cyrillic',			'label' => Mage::helper('hybrid')->__('Cyrillic')),
			array('value' => 'cyrillic-ext',		'label' => Mage::helper('hybrid')->__('Cyrillic Extended')),
			array('value' => 'greek',				'label' => Mage::helper('hybrid')->__('Greek')),
			array('value' => 'greek-ext',			'label' => Mage::helper('hybrid')->__('Greek Extended')),
			array('value' => 'khmer',				'label' => Mage::helper('hybrid')->__('Khmer')),
			array('value' => 'latin',				'label' => Mage::helper('hybrid')->__('Latin')),
			array('value' => 'latin-ext',			'label' => Mage::helper('hybrid')->__('Latin Extended')),
			array('value' => 'vietnamese',			'label' => Mage::helper('hybrid')->__('Vietnamese')),
		);
	}
}