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
 * Category RSS block
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Blog
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Blog_Block_Category_Rss
    extends Mage_Rss_Block_Abstract {
    /**
     * Cache tag constant for feed reviews
     * @var string
     */
    const CACHE_TAG = 'block_html_blog_category_rss';
    /**
     * constructor
     * @access protected
     * @return void
     * @author Zozoconcepts Hybrid
     */
    protected function _construct(){
        $this->setCacheTags(array(self::CACHE_TAG));
        /*
        * setting cache to save the rss for 10 minutes
        */
        $this->setCacheKey('zozoconcepts_blog_category_rss');
        $this->setCacheLifetime(600);
    }
    /**
     * toHtml method
     * @access protected
     * @return string
     * @author Zozoconcepts Hybrid
     */
    protected function _toHtml(){
        $url = Mage::helper('zozoconcepts_blog/category')->getCategoriesUrl();
        $title = Mage::helper('zozoconcepts_blog')->__('Categories');
        $rssObj = Mage::getModel('rss/rss');
        $data = array(
            'title' => $title,
            'description' => $title,
            'link'=> $url,
            'charset' => 'UTF-8',
        );
        $rssObj->_addHeader($data);
        $collection = Mage::getModel('zozoconcepts_blog/category')->getCollection()
            ->addFieldToFilter('status', 1)
            ->addStoreFilter(Mage::app()->getStore())
            ->addFieldToFilter('in_rss', 1)
            ->setOrder('created_at');
        $collection->load();
        foreach ($collection as $item){
            $description = '<p>';
            $description .= '<div>'.Mage::helper('zozoconcepts_blog')->__('Name').': '.$item->getCatName().'</div>';
            $description .= '<div>'.Mage::helper('zozoconcepts_blog')->__('Category Description').': '.$item->getCatDesc().'</div>';
            $description .= '</p>';
            $data = array(
                'title'=>$item->getCatName(),
                'link'=>$item->getCategoryUrl(),
                'description' => $description
            );
            $rssObj->_addEntry($data);
        }
        return $rssObj->createRssXml();
    }
}
