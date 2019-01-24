<?php
/**
 * Zozoconcepts_Hybrid extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Zozoconcepts
 * @package        Zozoconcepts_Hybrid
 * @copyright      Copyright (c) 2014
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * 
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Hybrid
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Hybrid_Model_Icons_Headercart
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'fa-shopping-cart', 'label'=>Mage::helper('hybrid')->__('fa-shopping-cart')),
            array('value'=>'fa-download', 'label'=>Mage::helper('hybrid')->__('fa-download')),
            array('value'=>'fa-truck', 'label'=>Mage::helper('hybrid')->__('fa-truck')),
            array('value'=>'fa-barcode', 'label'=>Mage::helper('hybrid')->__('fa-barcode')),
            array('value'=>'fa-archive', 'label'=>Mage::helper('hybrid')->__('fa-archive')),
            array('value'=>'fa-suitcase', 'label'=>Mage::helper('hybrid')->__('fa-suitcase'))
        );
    }

}