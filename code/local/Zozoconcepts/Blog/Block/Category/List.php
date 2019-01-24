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
 * Category list block
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Blog
 * @author Zozoconcepts Hybrid
 */
class Zozoconcepts_Blog_Block_Category_List
    extends Mage_Core_Block_Template {
    /**
     * initialize
     * @access public
     * @author Zozoconcepts Hybrid
     */
     public function __construct(){
        parent::__construct();
         $categories = Mage::getResourceModel('zozoconcepts_blog/category_collection')
                         ->addStoreFilter(Mage::app()->getStore())
                         ->addFieldToFilter('status', 1);
        $categories->setOrder('cat_name', 'asc');
        $this->setCategories($categories);
    }
    /**
     * prepare the layout
     * @access protected
     * @return Zozoconcepts_Blog_Block_Category_List
     * @author Zozoconcepts Hybrid
     */
    protected function _prepareLayout(){
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock('page/html_pager', 'zozoconcepts_blog.category.html.pager')
            ->setCollection($this->getCategories());
        $this->setChild('pager', $pager);
        $this->getCategories()->load();
        return $this;
    }
    /**
     * get the pager html
     * @access public
     * @return string
     * @author Zozoconcepts Hybrid
     */
    public function getPagerHtml(){
        return $this->getChildHtml('pager');
    }
}
