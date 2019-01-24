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
 * Brand - product relation model
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Brands
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Brands_Model_Resource_Brand_Product
    extends Mage_Core_Model_Resource_Db_Abstract {
    /**
     * initialize resource model
     * @access protected
     * @see Mage_Core_Model_Resource_Abstract::_construct()
     * @author Zozoconcepts Hybrid
     */
    protected function  _construct(){
        $this->_init('zozoconcepts_brands/brand_product', 'rel_id');
    }
    /**
     * Save brand - product relations
     * @access public
     * @param Zozoconcepts_Brands_Model_Brand $brand
     * @param array $data
     * @return Zozoconcepts_Brands_Model_Resource_Brand_Product
     * @author Zozoconcepts Hybrid
     */
    public function saveBrandRelation($brand, $data){
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('brand_id=?', $brand->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $productId => $info) {
            $this->_getWriteAdapter()->insert($this->getMainTable(), array(
                'brand_id'      => $brand->getId(),
                'product_id'     => $productId,
                'position'      => @$info['position']
            ));
        }
        return $this;
    }
    /**
     * Save  product - brand relations
     * @access public
     * @param Mage_Catalog_Model_Product $prooduct
     * @param array $data
     * @return Zozoconcepts_Brands_Model_Resource_Brand_Product
     * @@author Zozoconcepts Hybrid
     */
    public function saveProductRelation($product, $data){
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('product_id=?', $product->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $brandId => $info) {
            $this->_getWriteAdapter()->insert($this->getMainTable(), array(
                'brand_id' => $brandId,
                'product_id' => $product->getId(),
                'position'   => @$info['position']
            ));
        }
        return $this;
    }
}
