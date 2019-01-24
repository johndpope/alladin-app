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
 * Brand - product relation resource model collection
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Brands
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Brands_Model_Resource_Brand_Product_Collection
    extends Mage_Catalog_Model_Resource_Product_Collection {
    /**
     * remember if fields have been joined
     * @var bool
     */
    protected $_joinedFields = false;
    /**
     * join the link table
     * @access public
     * @return Zozoconcepts_Brands_Model_Resource_Brand_Product_Collection
     * @author Zozoconcepts Hybrid
     */
    public function joinFields(){
        if (!$this->_joinedFields){
            $this->getSelect()->join(
                array('related' => $this->getTable('zozoconcepts_brands/brand_product')),
                'related.product_id = e.entity_id',
                array('position')
            );
            $this->_joinedFields = true;
        }
        return $this;
    }
    /**
     * add brand filter
     * @access public
     * @param Zozoconcepts_Brands_Model_Brand | int $brand
     * @return Zozoconcepts_Brands_Model_Resource_Brand_Product_Collection
     * @author Zozoconcepts Hybrid
     */
    public function addBrandFilter($brand){
        if ($brand instanceof Zozoconcepts_Brands_Model_Brand){
            $brand = $brand->getId();
        }
        if (!$this->_joinedFields){
            $this->joinFields();
        }
        $this->getSelect()->where('related.brand_id = ?', $brand);
        return $this;
    }
}
