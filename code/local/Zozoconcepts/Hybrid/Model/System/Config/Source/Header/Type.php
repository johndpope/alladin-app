<?php

class Zozoconcepts_Hybrid_Model_System_Config_Source_Header_Type
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'page/html/header_type1.phtml', 'label' => Mage::helper('hybrid')->__('Type 1 (Default)')),
            array('value' => 'page/html/header_type2.phtml', 'label' => Mage::helper('hybrid')->__('Type 2')),
            array('value' => 'page/html/header_type3.phtml', 'label' => Mage::helper('hybrid')->__('Type 3')),
            array('value' => 'page/html/header_type4.phtml', 'label' => Mage::helper('hybrid')->__('Type 4')),
            array('value' => 'page/html/header_type5.phtml', 'label' => Mage::helper('hybrid')->__('Type 5')),
            array('value' => 'page/html/header_type6.phtml', 'label' => Mage::helper('hybrid')->__('Type 6 (Transparent)')),
            array('value' => 'page/html/header_type7.phtml', 'label' => Mage::helper('hybrid')->__('Type 7 (Semi transparent light)')),
            array('value' => 'page/html/header_type8.phtml', 'label' => Mage::helper('hybrid')->__('Type 8 (Semi transparent dark)')),
           /*array('value' => 'page/html/header_type9.phtml', 'label' => Mage::helper('hybrid')->__('Type 9')),
            array('value' => 'page/html/header_type10.phtml', 'label' => Mage::helper('hybrid')->__('Type 10')),
            array('value' => 'page/html/header_type11.phtml', 'label' => Mage::helper('hybrid')->__('Type 11')),
            array('value' => 'page/html/header_type12.phtml', 'label' => Mage::helper('hybrid')->__('Type 12')),
            array('value' => 'page/html/header_type13.phtml', 'label' => Mage::helper('hybrid')->__('Type 13')),
            array('value' => 'page/html/header_type14.phtml', 'label' => Mage::helper('hybrid')->__('Type 14'))*/ 
       );
    }
}