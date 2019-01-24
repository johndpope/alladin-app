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
class Zozoconcepts_Hybrid_Model_Icons_Emailfiend
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'\f003', 'label'=>Mage::helper('hybrid')->__('fa-envelope-o')), // fa-envelope-o
            array('value'=>'\f0e0', 'label'=>Mage::helper('hybrid')->__('fa-envelope')), // fa-envelope
            array('value'=>'\f135', 'label'=>Mage::helper('hybrid')->__('fa-rocket')), // fa-rocket
            array('value'=>'\f1d8', 'label'=>Mage::helper('hybrid')->__('fa-paper-plane')), // fa-paper-plane 
            array('value'=>'\f1d9', 'label'=>Mage::helper('hybrid')->__('fa-paper-plane-o')), // fa-paper-plane-o
            array('value'=>'\f0a1', 'label'=>Mage::helper('hybrid')->__('fa-bullhorn')) //fa-bullhorn 
        );
    }

}