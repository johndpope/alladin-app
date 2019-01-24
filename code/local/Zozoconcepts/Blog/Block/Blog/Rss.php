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
 * Blog RSS block
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Blog
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Blog_Block_Blog_Rss
    extends Mage_Rss_Block_Abstract {
    /**
     * Cache tag constant for feed reviews
     * @var string
     */
    const CACHE_TAG = 'block_html_blog_blog_rss';
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
        $this->setCacheKey('zozoconcepts_blog_blog_rss');
        $this->setCacheLifetime(600);
    }
    /**
     * toHtml method
     * @access protected
     * @return string
     * @author Zozoconcepts Hybrid
     */
    protected function _toHtml(){
        $url = Mage::helper('zozoconcepts_blog/blog')->getBlogsUrl();
        $title = Mage::helper('zozoconcepts_blog')->__('Blogs');
        $rssObj = Mage::getModel('rss/rss');
        $data = array(
            'title' => $title,
            'description' => $title,
            'link'=> $url,
            'charset' => 'UTF-8',
        );
        $rssObj->_addHeader($data);
        $collection = Mage::getModel('zozoconcepts_blog/blog')->getCollection()
            ->addFieldToFilter('status', 1)
            ->addStoreFilter(Mage::app()->getStore())
            ->addFieldToFilter('in_rss', 1)
            ->setOrder('created_at');
        $collection->load();
        foreach ($collection as $item){
            $description = '<p>';
            $description .= '<div>'.Mage::helper('zozoconcepts_blog')->__('Title').': '.$item->getTitle().'</div>';
            $description .= '<div>'.Mage::helper('zozoconcepts_blog')->__('Excerpt').': '.$item->getExcerpt().'</div>';
            $description .= '<div>'.Mage::helper('zozoconcepts_blog')->__('Full Description').': '.$item->getFullDescription().'</div>';
            if ($item->getFeaturedImage()) {
                $description .= '<div>';
                $description .= Mage::helper('zozoconcepts_blog')->__('Featured Image');
                $description .= '<img src="'.Mage::helper('zozoconcepts_blog/blog_image')->init($item, 'featured_image')->resize(75).'" alt="'.$this->htmlEscape($item->getTitle()).'" />';
                $description .= '</div>';
            }
            $description .= '<div>'.Mage::helper('zozoconcepts_blog')->__("Show on Slider").':'.(($item->getShowOnslide() == 1) ? Mage::helper('zozoconcepts_blog')->__('Yes') : Mage::helper('zozoconcepts_blog')->__('No')).'</div>';
            $description .= '</p>';
            $data = array(
                'title'=>$item->getTitle(),
                'link'=>$item->getBlogUrl(),
                'description' => $description
            );
            $rssObj->_addEntry($data);
        }
        return $rssObj->createRssXml();
    }
}
