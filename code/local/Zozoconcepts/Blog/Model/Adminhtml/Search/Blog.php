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
class Zozoconcepts_Blog_Model_Adminhtml_Search_Blog
    extends Varien_Object {
    /**
     * Load search results
     * @access public
     * @return Zozoconcepts_Blog_Model_Adminhtml_Search_Blog
     * @author Zozoconcepts Hybrid
     */
    public function load(){
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('zozoconcepts_blog/blog_collection')
            ->addFieldToFilter('title', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $blog) {
            $arr[] = array(
                'id'=> 'blog/1/'.$blog->getId(),
                'type'  => Mage::helper('zozoconcepts_blog')->__('Blog'),
                'name'  => $blog->getTitle(),
                'description'   => $blog->getTitle(),
                'url' => Mage::helper('adminhtml')->getUrl('*/blog_blog/edit', array('id'=>$blog->getId())),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
