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
 * Blog list block
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Blog
 * @author Zozoconcepts Hybrid
 */
class Zozoconcepts_Blog_Block_Blog_List
    extends Mage_Core_Block_Template {
    /**
     * initialize
     * @access public
     * @author Zozoconcepts Hybrid
     */
     public function __construct(){
        parent::__construct();
         $blogs = Mage::getResourceModel('zozoconcepts_blog/blog_collection')
                         ->addStoreFilter(Mage::app()->getStore())
                         ->addFieldToFilter('status', 1);
        $blogs->setOrder('created_at', 'desc');
        $this->setBlogs($blogs);
    }
    /**
     * prepare the layout
     * @access protected
     * @return Zozoconcepts_Blog_Block_Blog_List
     * @author Zozoconcepts Hybrid
     */
    protected function _prepareLayout(){
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock('page/html_pager', 'zozoconcepts_blog.blog.html.pager')
            ->setCollection($this->getBlogs());
        $this->setChild('pager', $pager);
        $this->getBlogs()->load();
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
	/**
     * set string limit
     * @access public
     * @return string
     * @author Zozoconcepts Hybrid
     */
	public function cut_string($string,$number){
		if(strlen($string) <= $number) {
			return $string;
		}
		else {	
			if(strpos($string," ",$number) > $number){
				$new_space = strpos($string," ",$number);
				$new_string = substr($string,0,$new_space)."..";
				return $new_string;
			}
			$new_string = substr($string,0,$number)."..";
			return $new_string;
		}
	}
}
