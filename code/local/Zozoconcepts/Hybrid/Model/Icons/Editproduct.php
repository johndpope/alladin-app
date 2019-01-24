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
class Zozoconcepts_Hybrid_Model_Icons_Editproduct
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'fa-pencil', 'label'=>Mage::helper('hybrid')->__('fa-pencil')),
            array('value'=>'fa-eraser', 'label'=>Mage::helper('hybrid')->__('fa-eraser')),
            array('value'=>'fa-undo', 'label'=>Mage::helper('hybrid')->__('fa-undo')),
            array('value'=>'fa-wrench', 'label'=>Mage::helper('hybrid')->__('fa-wrench')),
            array('value'=>'fa-cogs', 'label'=>Mage::helper('hybrid')->__('fa-cogs')),
            array('value'=>'fa-pencil-square', 'label'=>Mage::helper('hybrid')->__('fa-pencil-square'))
        );
    }

}