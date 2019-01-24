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
 * Product helper
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Brands
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Brands_Helper_Product
    extends Zozoconcepts_Brands_Helper_Data {
    /**
     * get the selected brands for a product
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return array()
     * @author Zozoconcepts Hybrid
     */
    public function getSelectedBrands(Mage_Catalog_Model_Product $product){
        if (!$product->hasSelectedBrands()) {
            $brands = array();
            foreach ($this->getSelectedBrandsCollection($product) as $brand) {
                $brands[] = $brand;
            }
            $product->setSelectedBrands($brands);
        }
        return $product->getData('selected_brands');
    }
    /**
     * get brand collection for a product
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return Zozoconcepts_Brands_Model_Resource_Brand_Collection
     * @author Zozoconcepts Hybrid
     */
    public function getSelectedBrandsCollection(Mage_Catalog_Model_Product $product){
        $collection = Mage::getResourceSingleton('zozoconcepts_brands/brand_collection')
            ->addProductFilter($product);
        return $collection;
    }
}
