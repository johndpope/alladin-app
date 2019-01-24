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
class Zozoconcepts_Hybrid_Model_Icons_Fancybox
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'fa-search', 'label'=>Mage::helper('hybrid')->__('fa-search')),
            array('value'=>'fa-camera', 'label'=>Mage::helper('hybrid')->__('fa-camera')),
            array('value'=>'fa-eye', 'label'=>Mage::helper('hybrid')->__('fa-eye')),
            array('value'=>'fa-cog', 'label'=>Mage::helper('hybrid')->__('fa-cog')),
            array('value'=>'fa-eye-slash', 'label'=>Mage::helper('hybrid')->__('fa-eye-slash')),
            array('value'=>'fa-search-plus', 'label'=>Mage::helper('hybrid')->__('fa-search-plus'))
        );
    }

}