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
class Zozoconcepts_Hybrid_Model_Icons_Search
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'fa-search', 'label'=>Mage::helper('hybrid')->__('fa-search')),
            array('value'=>'fa-arrow-circle-right', 'label'=>Mage::helper('hybrid')->__('fa-arrow-circle-right')),
            array('value'=>'fa-eye', 'label'=>Mage::helper('hybrid')->__('fa-eye')),
            array('value'=>'fa-search-plus', 'label'=>Mage::helper('hybrid')->__('fa-search-plus')),
            array('value'=>'fa-neuter', 'label'=>Mage::helper('hybrid')->__('fa-neuter')),
            array('value'=>'fa-filter', 'label'=>Mage::helper('hybrid')->__('fa-filter'))
        );
    }

}