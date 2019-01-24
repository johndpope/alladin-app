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
 * Brand model
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Brands
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Brands_Model_Brand
    extends Mage_Core_Model_Abstract {
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'zozoconcepts_brands_brand';
    const CACHE_TAG = 'zozoconcepts_brands_brand';
    /**
     * Prefix of model events names
     * @var string
     */
    protected $_eventPrefix = 'zozoconcepts_brands_brand';

    /**
     * Parameter name in event
     * @var string
     */
    protected $_eventObject = 'brand';
    protected $_productInstance = null;
    /**
     * constructor
     * @access public
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function _construct(){
        parent::_construct();
        $this->_init('zozoconcepts_brands/brand');
    }
    /**
     * before save brand
     * @access protected
     * @return Zozoconcepts_Brands_Model_Brand
     * @author Zozoconcepts Hybrid
     */
    protected function _beforeSave(){
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()){
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }
    /**
     * get the url to the brand details page
     * @access public
     * @return string
     * @author Zozoconcepts Hybrid
     */
    public function getBrandUrl(){
        if ($this->getUrlKey()){
            $urlKey = '';
            if ($prefix = Mage::getStoreConfig('zozoconcepts_brands/brand/url_prefix')){
                $urlKey .= $prefix.'/';
            }
            $urlKey .= $this->getUrlKey();
            if ($suffix = Mage::getStoreConfig('zozoconcepts_brands/brand/url_suffix')){
                $urlKey .= '.'.$suffix;
            }
            return Mage::getUrl('', array('_direct'=>$urlKey));
        }
        return Mage::getUrl('zozoconcepts_brands/brand/view', array('id'=>$this->getId()));
    }
    /**
     * check URL key
     * @access public
     * @param string $urlKey
     * @param bool $active
     * @return mixed
     * @author Zozoconcepts Hybrid
     */
    public function checkUrlKey($urlKey, $active = true){
        return $this->_getResource()->checkUrlKey($urlKey, $active);
    }

    /**
     * get the brand Brand Descriptions
     * @access public
     * @return string
     * @author Zozoconcepts Hybrid
     */
    public function getBrandDescriptions(){
        $brand_descriptions = $this->getData('brand_descriptions');
        $helper = Mage::helper('cms');
        $processor = $helper->getBlockTemplateProcessor();
        $html = $processor->filter($brand_descriptions);
        return $html;
    }
    /**
     * save brand relation
     * @access public
     * @return Zozoconcepts_Brands_Model_Brand
     * @author Zozoconcepts Hybrid
     */
    protected function _afterSave() {
        $this->getProductInstance()->saveBrandRelation($this);
        return parent::_afterSave();
    }
    /**
     * get product relation model
     * @access public
     * @return Zozoconcepts_Brands_Model_Brand_Product
     * @author Zozoconcepts Hybrid
     */
    public function getProductInstance(){
        if (!$this->_productInstance) {
            $this->_productInstance = Mage::getSingleton('zozoconcepts_brands/brand_product');
        }
        return $this->_productInstance;
    }
    /**
     * get selected products array
     * @access public
     * @return array
     * @author Zozoconcepts Hybrid
     */
    public function getSelectedProducts(){
        if (!$this->hasSelectedProducts()) {
            $products = array();
            foreach ($this->getSelectedProductsCollection() as $product) {
                $products[] = $product;
            }
            $this->setSelectedProducts($products);
        }
        return $this->getData('selected_products');
    }
    /**
     * Retrieve collection selected products
     * @access public
     * @return Zozoconcepts_Brands_Resource_Brand_Product_Collection
     * @author Zozoconcepts Hybrid
     */
    public function getSelectedProductsCollection(){
        $collection = $this->getProductInstance()->getProductCollection($this);
        return $collection;
    }
    /**
     * get default values
     * @access public
     * @return array
     * @author Zozoconcepts Hybrid
     */
    public function getDefaultValues() {
        $values = array();
        $values['status'] = 1;
        return $values;
    }
}
