<?php
/**
 * Zozoconcepts_Blog extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Zozoconcepts
 * @package        Zozoconcepts_Blog
 * @copyright      Copyright (c) 2014
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Category model
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Blog
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Blog_Model_Category
    extends Mage_Core_Model_Abstract {
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'zozoconcepts_blog_category';
    const CACHE_TAG = 'zozoconcepts_blog_category';
    /**
     * Prefix of model events names
     * @var string
     */
    protected $_eventPrefix = 'zozoconcepts_blog_category';

    /**
     * Parameter name in event
     * @var string
     */
    protected $_eventObject = 'category';
    /**
     * constructor
     * @access public
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function _construct(){
        parent::_construct();
        $this->_init('zozoconcepts_blog/category');
    }
    /**
     * before save category
     * @access protected
     * @return Zozoconcepts_Blog_Model_Category
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
     * get the url to the category details page
     * @access public
     * @return string
     * @author Zozoconcepts Hybrid
     */
    public function getCategoryUrl(){
        if ($this->getUrlKey()){
            $urlKey = '';
            if ($prefix = Mage::getStoreConfig('zozoconcepts_blog/category/url_prefix')){
                $urlKey .= $prefix.'/';
            }
            $urlKey .= $this->getUrlKey();
            if ($suffix = Mage::getStoreConfig('zozoconcepts_blog/category/url_suffix')){
                $urlKey .= '.'.$suffix;
            }
            return Mage::getUrl('', array('_direct'=>$urlKey));
        }
        return Mage::getUrl('zozoconcepts_blog/category/view', array('id'=>$this->getId()));
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
     * get the category Category Description
     * @access public
     * @return string
     * @author Zozoconcepts Hybrid
     */
    public function getCatDesc(){
        $cat_desc = $this->getData('cat_desc');
        $helper = Mage::helper('cms');
        $processor = $helper->getBlockTemplateProcessor();
        $html = $processor->filter($cat_desc);
        return $html;
    }
    /**
     * save category relation
     * @access public
     * @return Zozoconcepts_Blog_Model_Category
     * @author Zozoconcepts Hybrid
     */
    protected function _afterSave() {
        return parent::_afterSave();
    }
    /**
     * Retrieve  collection
     * @access public
     * @return Zozoconcepts_Blog_Model_Blog_Collection
     * @author Zozoconcepts Hybrid
     */
    public function getSelectedBlogsCollection(){
        if (!$this->hasData('_blog_collection')) {
            if (!$this->getId()) {
                return new Varien_Data_Collection();
            }
            else {
                $collection = Mage::getResourceModel('zozoconcepts_blog/blog_collection')
                        ->addFieldToFilter('category_id', $this->getId());
                $this->setData('_blog_collection', $collection);
            }
        }
        return $this->getData('_blog_collection');
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
        $values['in_rss'] = 1;
        return $values;
    }
}
