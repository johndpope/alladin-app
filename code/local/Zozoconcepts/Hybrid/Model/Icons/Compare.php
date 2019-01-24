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
class Zozoconcepts_Hybrid_Model_Icons_Compare
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'fa-signal', 'label'=>Mage::helper('hybrid')->__('fa-signal')),
            array('value'=>'fa-compress', 'label'=>Mage::helper('hybrid')->__('fa-compress')),
            array('value'=>'fa-exchange', 'label'=>Mage::helper('hybrid')->__('fa-exchange')),
            array('value'=>'fa-arrows-alt', 'label'=>Mage::helper('hybrid')->__('fa-arrows-alt')),
            array('value'=>'fa-bar-chart-o', 'label'=>Mage::helper('hybrid')->__('fa-bar-chart-o')),
            array('value'=>'fa-random', 'label'=>Mage::helper('hybrid')->__('fa-random'))
        );
    }

}