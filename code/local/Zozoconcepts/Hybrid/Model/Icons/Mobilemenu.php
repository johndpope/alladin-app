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
class Zozoconcepts_Hybrid_Model_Icons_Mobilemenu
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'fa-bars', 'label'=>Mage::helper('hybrid')->__('fa-bars')),
            array('value'=>'fa-tasks', 'label'=>Mage::helper('hybrid')->__('fa-tasks')),
            array('value'=>'fa-align-center', 'label'=>Mage::helper('hybrid')->__('fa-align-center')),
            array('value'=>'fa-list-alt', 'label'=>Mage::helper('hybrid')->__('fa-list-alt')),
            array('value'=>'fa-align-left', 'label'=>Mage::helper('hybrid')->__('fa-align-left')),
            array('value'=>'fa-qrcode', 'label'=>Mage::helper('hybrid')->__('fa-qrcode'))
        );
    }

}