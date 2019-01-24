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
class Zozoconcepts_Hybrid_Model_Icons_Wishlist
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'fa-heart-o', 'label'=>Mage::helper('hybrid')->__('fa-heart-o')),
            array('value'=>'fa-thumbs-o-up', 'label'=>Mage::helper('hybrid')->__('fa-thumbs-o-up')),
            array('value'=>'fa-star', 'label'=>Mage::helper('hybrid')->__('fa-star')),
            array('value'=>'fa-thumbs-up', 'label'=>Mage::helper('hybrid')->__('fa-thumbs-up')),
            array('value'=>'fa-heart', 'label'=>Mage::helper('hybrid')->__('fa-heart')),
            array('value'=>'fa-lightbulb-o', 'label'=>Mage::helper('hybrid')->__('fa-lightbulb-o'))
        );
    }

}