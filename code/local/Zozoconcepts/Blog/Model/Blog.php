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
 * Blog model
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Blog
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Blog_Model_Blog
    extends Mage_Core_Model_Abstract {
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'zozoconcepts_blog_blog';
    const CACHE_TAG = 'zozoconcepts_blog_blog';
    /**
     * Prefix of model events names
     * @var string
     */
    protected $_eventPrefix = 'zozoconcepts_blog_blog';

    /**
     * Parameter name in event
     * @var string
     */
    protected $_eventObject = 'blog';
    /**
     * constructor
     * @access public
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function _construct(){
        parent::_construct();
        $this->_init('zozoconcepts_blog/blog');
    }
    /**
     * before save blog
     * @access protected
     * @return Zozoconcepts_Blog_Model_Blog
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
     * get the url to the blog details page
     * @access public
     * @return string
     * @author Zozoconcepts Hybrid
     */
    public function getBlogUrl(){
        if ($this->getUrlKey()){
            $urlKey = '';
            if ($prefix = Mage::getStoreConfig('zozoconcepts_blog/blog/url_prefix')){
                $urlKey .= $prefix.'/';
            }
            $urlKey .= $this->getUrlKey();
            if ($suffix = Mage::getStoreConfig('zozoconcepts_blog/blog/url_suffix')){
                $urlKey .= '.'.$suffix;
            }
            return Mage::getUrl('', array('_direct'=>$urlKey));
        }
        return Mage::getUrl('zozoconcepts_blog/blog/view', array('id'=>$this->getId()));
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
     * get the blog Excerpt
     * @access public
     * @return string
     * @author Zozoconcepts Hybrid
     */
    public function getExcerpt(){
        $excerpt = $this->getData('excerpt');
        $helper = Mage::helper('cms');
        $processor = $helper->getBlockTemplateProcessor();
        $html = $processor->filter($excerpt);
        return $html;
    }
    /**
     * get the blog Full Description
     * @access public
     * @return string
     * @author Zozoconcepts Hybrid
     */
    public function getFullDescription(){
        $full_description = $this->getData('full_description');
        $helper = Mage::helper('cms');
        $processor = $helper->getBlockTemplateProcessor();
        $html = $processor->filter($full_description);
        return $html;
    }
    /**
     * save blog relation
     * @access public
     * @return Zozoconcepts_Blog_Model_Blog
     * @author Zozoconcepts Hybrid
     */
    protected function _afterSave() {
        return parent::_afterSave();
    }
    /**
     * Retrieve parent 
     * @access public
     * @return null|Zozoconcepts_Blog_Model_Category
     * @author Zozoconcepts Hybrid
     */
    public function getParentCategory(){
        if (!$this->hasData('_parent_category')) {
            if (!$this->getCategoryId()) {
                return null;
            }
            else {
                $category = Mage::getModel('zozoconcepts_blog/category')->load($this->getCategoryId());
				//iterate category
				foreach($category as $categories)
				{
					if ($categories->getId()) {
						$this->setData('_parent_category', $categories);
					}
					else {
						$this->setData('_parent_category', null);
					}
				}
                
            }
        }
        return $this->getData('_parent_category');
    }
    /**
     * check if comments are allowed
     * @access public
     * @return array
     * @author Zozoconcepts Hybrid
     */
    public function getAllowComments() {
        if ($this->getData('allow_comment') == Zozoconcepts_Blog_Model_Adminhtml_Source_Yesnodefault::NO) {
            return false;
        }
        if ($this->getData('allow_comment') == Zozoconcepts_Blog_Model_Adminhtml_Source_Yesnodefault::YES) {
            return true;
        }
        return Mage::getStoreConfigFlag('zozoconcepts_blog/blog/allow_comment');
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
        $values['allow_comment'] = Zozoconcepts_Blog_Model_Adminhtml_Source_Yesnodefault::USE_DEFAULT;
        return $values;
    }
}
