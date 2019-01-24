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
 * Brand product list
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Brands
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Brands_Block_Brand_Catalog_Product_List extends Mage_Core_Block_Template {
    /**
     * get the list of products
     * @access public
     * @return Mage_Catalog_Model_Resource_Product_Collection
     * @author Zozoconcepts Hybrid
     */
    public function getProductCollection() {
        $collection = $this->getBrand()->getSelectedProductsCollection();
        $collection->addAttributeToSelect('*');
        $collection->addUrlRewrite();
        $collection->getSelect()->order('related.position');
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);
        return $collection;
    }
    /**
     * get current brand
     * @access public
     * @return Zozoconcepts_Brands_Model_Brand
     * @author Zozoconcepts Hybrid
     */
    public function getBrand() {
        return Mage::registry('current_brand');
    }
}