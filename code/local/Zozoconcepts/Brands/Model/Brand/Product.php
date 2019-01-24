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
 * Brand product model
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Brands
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Brands_Model_Brand_Product
    extends Mage_Core_Model_Abstract {
    /**
     * Initialize resource
     * @access protected
     * @return void
     * @author Zozoconcepts Hybrid
     */
    protected function _construct(){
        $this->_init('zozoconcepts_brands/brand_product');
    }
    /**
     * Save data for brand-product relation
     * @access public
     * @param  Zozoconcepts_Brands_Model_Brand $brand
     * @return Zozoconcepts_Brands_Model_Brand_Product
     * @author Zozoconcepts Hybrid
     */
    public function saveBrandRelation($brand){
        $data = $brand->getProductsData();
        if (!is_null($data)) {
            $this->_getResource()->saveBrandRelation($brand, $data);
        }
        return $this;
    }
    /**
     * get products for brand
     * @access public
     * @param Zozoconcepts_Brands_Model_Brand $brand
     * @return Zozoconcepts_Brands_Model_Resource_Brand_Product_Collection
     * @author Zozoconcepts Hybrid
     */
    public function getProductCollection($brand){
        $collection = Mage::getResourceModel('zozoconcepts_brands/brand_product_collection')
            ->addBrandFilter($brand);
        return $collection;
    }
}
