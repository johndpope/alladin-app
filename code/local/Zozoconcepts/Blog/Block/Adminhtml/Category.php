<?php
/**
 * Zozoconcepts_Blog extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Zozoconcepts
 * @package        Zozoconcepts_Blog
 * @copyright      Copyright (c) 2014
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Category admin block
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Blog
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Blog_Block_Adminhtml_Category
    extends Mage_Adminhtml_Block_Widget_Grid_Container {
    /**
     * constructor
     * @access public
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function __construct(){
        $this->_controller         = 'adminhtml_category';
        $this->_blockGroup         = 'zozoconcepts_blog';
        parent::__construct();
        $this->_headerText         = Mage::helper('zozoconcepts_blog')->__('Category');
        $this->_updateButton('add', 'label', Mage::helper('zozoconcepts_blog')->__('Add Category'));

    }
}
