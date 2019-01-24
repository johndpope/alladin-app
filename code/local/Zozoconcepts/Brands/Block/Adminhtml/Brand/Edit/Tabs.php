<?php
/**
 * Zozoconcepts_Brands extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Zozoconcepts
 * @package        Zozoconcepts_Brands
 * @copyright      Copyright (c) 2014
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Brand admin edit tabs
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Brands
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Brands_Block_Adminhtml_Brand_Edit_Tabs
    extends Mage_Adminhtml_Block_Widget_Tabs {
    /**
     * Initialize Tabs
     * @access public
     * @author Zozoconcepts Hybrid
     */
    public function __construct() {
        parent::__construct();
        $this->setId('brand_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('zozoconcepts_brands')->__('Brand'));
    }
    /**
     * before render html
     * @access protected
     * @return Zozoconcepts_Brands_Block_Adminhtml_Brand_Edit_Tabs
     * @author Zozoconcepts Hybrid
     */
    protected function _beforeToHtml(){
        $this->addTab('form_brand', array(
            'label'        => Mage::helper('zozoconcepts_brands')->__('Brand'),
            'title'        => Mage::helper('zozoconcepts_brands')->__('Brand'),
            'content'     => $this->getLayout()->createBlock('zozoconcepts_brands/adminhtml_brand_edit_tab_form')->toHtml(),
        ));
        $this->addTab('form_meta_brand', array(
            'label'        => Mage::helper('zozoconcepts_brands')->__('Meta'),
            'title'        => Mage::helper('zozoconcepts_brands')->__('Meta'),
            'content'     => $this->getLayout()->createBlock('zozoconcepts_brands/adminhtml_brand_edit_tab_meta')->toHtml(),
        ));
        if (!Mage::app()->isSingleStoreMode()){
            $this->addTab('form_store_brand', array(
                'label'        => Mage::helper('zozoconcepts_brands')->__('Store views'),
                'title'        => Mage::helper('zozoconcepts_brands')->__('Store views'),
                'content'     => $this->getLayout()->createBlock('zozoconcepts_brands/adminhtml_brand_edit_tab_stores')->toHtml(),
            ));
        }
        $this->addTab('products', array(
            'label' => Mage::helper('zozoconcepts_brands')->__('Associated products'),
            'url'   => $this->getUrl('*/*/products', array('_current' => true)),
            'class'    => 'ajax'
        ));
        return parent::_beforeToHtml();
    }
    /**
     * Retrieve brand entity
     * @access public
     * @return Zozoconcepts_Brands_Model_Brand
     * @author Zozoconcepts Hybrid
     */
    public function getBrand(){
        return Mage::registry('current_brand');
    }
}
