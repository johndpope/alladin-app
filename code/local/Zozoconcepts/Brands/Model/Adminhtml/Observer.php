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
 * Adminhtml observer
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Brands
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Brands_Model_Adminhtml_Observer {
    /**
     * check if tab can be added
     * @access protected
     * @param Mage_Catalog_Model_Product $product
     * @return bool
     * @author Zozoconcepts Hybrid
     */
    protected function _canAddTab($product){
        if ($product->getId()){
            return true;
        }
        if (!$product->getAttributeSetId()){
            return false;
        }
        $request = Mage::app()->getRequest();
        if ($request->getParam('type') == 'configurable'){
            if ($request->getParam('attributes')){
                return true;
            }
        }
        return false;
    }
    /**
     * add the brand tab to products
     * @access public
     * @param Varien_Event_Observer $observer
     * @return Zozoconcepts_Brands_Model_Adminhtml_Observer
     * @author Zozoconcepts Hybrid
     */
    public function addProductBrandBlock($observer){
        $block = $observer->getEvent()->getBlock();
        $product = Mage::registry('product');
        if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs && $this->_canAddTab($product)){
            $block->addTab('brands', array(
                'label' => Mage::helper('zozoconcepts_brands')->__('Brands'),
                'url'   => Mage::helper('adminhtml')->getUrl('adminhtml/brands_brand_catalog_product/brands', array('_current' => true)),
                'class' => 'ajax',
            ));
        }
        return $this;
    }
    /**
     * save brand - product relation
     * @access public
     * @param Varien_Event_Observer $observer
     * @return Zozoconcepts_Brands_Model_Adminhtml_Observer
     * @author Zozoconcepts Hybrid
     */
    public function saveProductBrandData($observer){
        $post = Mage::app()->getRequest()->getPost('brands', -1);
        if ($post != '-1') {
            $post = Mage::helper('adminhtml/js')->decodeGridSerializedInput($post);
            $product = Mage::registry('product');
            $brandProduct = Mage::getResourceSingleton('zozoconcepts_brands/brand_product')->saveProductRelation($product, $post);
        }
        return $this;
    }}
