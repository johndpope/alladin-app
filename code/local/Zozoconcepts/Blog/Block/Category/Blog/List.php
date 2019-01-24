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
 * Category Blogs list block
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Blog
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Blog_Block_Category_Blog_List
    extends Zozoconcepts_Blog_Block_Blog_List {
    /**
     * initialize
     * @access public
     * @author Zozoconcepts Hybrid
     */
     public function __construct(){
        parent::__construct();
        $category = $this->getCategory();
        if ($category){
            $this->getBlogs()->addFieldToFilter('category_id', $category->getId());
        }
    }
    /**
     * prepare the layout - actually do nothing
     * @access protected
     * @return Zozoconcepts_Blog_Block_Category_Blog_List
     * @author Zozoconcepts Hybrid
     */
    protected function _prepareLayout(){
        return $this;
    }
    /**
     * get the current category
     * @access public
     * @return Zozoconcepts_Blog_Model_Category
     * @author Zozoconcepts Hybrid
     */
    public function getCategory(){
        return Mage::registry('current_category');
    }
}
