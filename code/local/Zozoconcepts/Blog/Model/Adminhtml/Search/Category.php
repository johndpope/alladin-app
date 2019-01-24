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
 * Admin search model
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Blog
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Blog_Model_Adminhtml_Search_Category
    extends Varien_Object {
    /**
     * Load search results
     * @access public
     * @return Zozoconcepts_Blog_Model_Adminhtml_Search_Category
     * @author Zozoconcepts Hybrid
     */
    public function load(){
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('zozoconcepts_blog/category_collection')
            ->addFieldToFilter('cat_name', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $category) {
            $arr[] = array(
                'id'=> 'category/1/'.$category->getId(),
                'type'  => Mage::helper('zozoconcepts_blog')->__('Category'),
                'name'  => $category->getCatName(),
                'description'   => $category->getCatName(),
                'url' => Mage::helper('adminhtml')->getUrl('*/blog_category/edit', array('id'=>$category->getId())),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
