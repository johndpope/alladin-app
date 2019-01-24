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
 * Admin search model
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Brands
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Brands_Model_Adminhtml_Search_Brand
    extends Varien_Object {
    /**
     * Load search results
     * @access public
     * @return Zozoconcepts_Brands_Model_Adminhtml_Search_Brand
     * @author Zozoconcepts Hybrid
     */
    public function load(){
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('zozoconcepts_brands/brand_collection')
            ->addFieldToFilter('title', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $brand) {
            $arr[] = array(
                'id'=> 'brand/1/'.$brand->getId(),
                'type'  => Mage::helper('zozoconcepts_brands')->__('Brand'),
                'name'  => $brand->getTitle(),
                'description'   => $brand->getTitle(),
                'url' => Mage::helper('adminhtml')->getUrl('*/brands_brand/edit', array('id'=>$brand->getId())),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
