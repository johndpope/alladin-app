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
 * Category helper
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Blog
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Blog_Helper_Category
    extends Mage_Core_Helper_Abstract {
    /**
     * get the url to the categories list page
     * @access public
     * @return string
     * @author Zozoconcepts Hybrid
     */
    public function getCategoriesUrl(){
        return Mage::getUrl('zozoconcepts_blog/category/index');
    }
    /**
     * check if breadcrumbs can be used
     * @access public
     * @return bool
     * @author Zozoconcepts Hybrid
     */
    public function getUseBreadcrumbs(){
        return Mage::getStoreConfigFlag('zozoconcepts_blog/category/breadcrumbs');
    }
    /**
     * check if the rss for category is enabled
     * @access public
     * @return bool
     * @author Zozoconcepts Hybrid
     */
    public function isRssEnabled(){
        return  Mage::getStoreConfigFlag('rss/config/active') && Mage::getStoreConfigFlag('zozoconcepts_blog/category/rss');
    }
    /**
     * get the link to the category rss list
     * @access public
     * @return string
     * @author Zozoconcepts Hybrid
     */
    public function getRssUrl(){
        return Mage::getUrl('zozoconcepts_blog/category/rss');
    }
}
