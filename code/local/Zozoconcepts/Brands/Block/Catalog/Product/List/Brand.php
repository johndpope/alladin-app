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
 * Brand list on product page block
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Brands
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Brands_Block_Catalog_Product_List_Brand
    extends Mage_Catalog_Block_Product_Abstract {
    /**
     * get the list of brands
     * @access protected
     * @return Zozoconcepts_Brands_Model_Resource_Brand_Collection
     * @author Zozoconcepts Hybrid
     */
    public function getBrandCollection(){
        if (!$this->hasData('brand_collection')){
            $product = Mage::registry('product');
            $collection = Mage::getResourceSingleton('zozoconcepts_brands/brand_collection')
                ->addStoreFilter(Mage::app()->getStore())
                ->addFieldToFilter('status', 1)
                ->addProductFilter($product);
            $collection->getSelect()->order('related_product.position', 'ASC');
            $this->setData('brand_collection', $collection);
        }
        return $this->getData('brand_collection');
    }
}
