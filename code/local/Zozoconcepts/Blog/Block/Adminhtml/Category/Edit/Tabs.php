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
 * Category admin edit tabs
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Blog
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Blog_Block_Adminhtml_Category_Edit_Tabs
    extends Mage_Adminhtml_Block_Widget_Tabs {
    /**
     * Initialize Tabs
     * @access public
     * @author Zozoconcepts Hybrid
     */
    public function __construct() {
        parent::__construct();
        $this->setId('category_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('zozoconcepts_blog')->__('Category'));
    }
    /**
     * before render html
     * @access protected
     * @return Zozoconcepts_Blog_Block_Adminhtml_Category_Edit_Tabs
     * @author Zozoconcepts Hybrid
     */
    protected function _beforeToHtml(){
        $this->addTab('form_category', array(
            'label'        => Mage::helper('zozoconcepts_blog')->__('Category'),
            'title'        => Mage::helper('zozoconcepts_blog')->__('Category'),
            'content'     => $this->getLayout()->createBlock('zozoconcepts_blog/adminhtml_category_edit_tab_form')->toHtml(),
        ));
        $this->addTab('form_meta_category', array(
            'label'        => Mage::helper('zozoconcepts_blog')->__('Meta'),
            'title'        => Mage::helper('zozoconcepts_blog')->__('Meta'),
            'content'     => $this->getLayout()->createBlock('zozoconcepts_blog/adminhtml_category_edit_tab_meta')->toHtml(),
        ));
        if (!Mage::app()->isSingleStoreMode()){
            $this->addTab('form_store_category', array(
                'label'        => Mage::helper('zozoconcepts_blog')->__('Store views'),
                'title'        => Mage::helper('zozoconcepts_blog')->__('Store views'),
                'content'     => $this->getLayout()->createBlock('zozoconcepts_blog/adminhtml_category_edit_tab_stores')->toHtml(),
            ));
        }
        return parent::_beforeToHtml();
    }
    /**
     * Retrieve category entity
     * @access public
     * @return Zozoconcepts_Blog_Model_Category
     * @author Zozoconcepts Hybrid
     */
    public function getCategory(){
        return Mage::registry('current_category');
    }
}
