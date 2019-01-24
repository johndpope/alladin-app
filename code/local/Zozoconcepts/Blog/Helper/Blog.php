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
 * Blog helper
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Blog
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Blog_Helper_Blog
    extends Mage_Core_Helper_Abstract {
    /**
     * get the url to the blogs list page
     * @access public
     * @return string
     * @author Zozoconcepts Hybrid
     */
    public function getBlogsUrl(){
        return Mage::getUrl('zozoconcepts_blog/blog/index');
    }
    /**
     * check if breadcrumbs can be used
     * @access public
     * @return bool
     * @author Zozoconcepts Hybrid
     */
    public function getUseBreadcrumbs(){
        return Mage::getStoreConfigFlag('zozoconcepts_blog/blog/breadcrumbs');
    }
    /**
     * check if the rss for blog is enabled
     * @access public
     * @return bool
     * @author Zozoconcepts Hybrid
     */
    public function isRssEnabled(){
        return  Mage::getStoreConfigFlag('rss/config/active') && Mage::getStoreConfigFlag('zozoconcepts_blog/blog/rss');
    }
    /**
     * get the link to the blog rss list
     * @access public
     * @return string
     * @author Zozoconcepts Hybrid
     */
    public function getRssUrl(){
        return Mage::getUrl('zozoconcepts_blog/blog/rss');
    }
	/**
     * Check If blog on home page enabled
     * @access public
     * @return string
     * @author Zozoconcepts Hybrid
     */
    public function getShowonhomepage(){
        return Mage::getStoreConfig('zozoconcepts_blog/blog/showonhomepage');
    }
	/**
     * blog Title for home page
     * @access public
     * @return string
     * @author Zozoconcepts Hybrid
     */
    public function getTitle(){
        return Mage::getStoreConfig('zozoconcepts_blog/blog/listing_title');
    }
	/**
     * blog Title for home page
     * @access public
     * @return string
     * @author Zozoconcepts Hybrid
     */
    public function getDesc(){
        return Mage::getStoreConfig('zozoconcepts_blog/blog/listing_desc');
    }
	
	/**
     * get image thumbnail width
     * @access public
     * @return string
     * @author Zozoconcepts Hybrid
     */
    public function getWidth(){
        return Mage::getStoreConfig('zozoconcepts_blog/blog/img_width');
    }
	/**
     * get image thumbnail Height
     * @access public
     * @return string
     * @author Zozoconcepts Hybrid
     */
    public function getHeight(){
        return Mage::getStoreConfig('zozoconcepts_blog/blog/img_height');
    }
	/**
     * get excerpt limit
     * @access public
     * @return string
     * @author Zozoconcepts Hybrid
     */
    public function getExcerptLimit(){
        return Mage::getStoreConfig('zozoconcepts_blog/blog/excerpt_limit');
    }
	
}
