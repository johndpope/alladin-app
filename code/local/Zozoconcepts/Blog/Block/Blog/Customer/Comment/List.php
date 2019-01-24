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
 * Blog customer comments list
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Blog
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Blog_Block_Blog_Customer_Comment_List
    extends Mage_Customer_Block_Account_Dashboard {
    /**
     * Blog comments collection
     * @var Zozoconcepts_Blog_Model_Resource_Blog_Comment_Blog_Collection
     */
    protected $_collection;

    /**
     * Initializes collection
     * @access public
     * @author Zozoconcepts Hybrid
     */
    protected function _construct() {
        $this->_collection = Mage::getResourceModel('zozoconcepts_blog/blog_comment_blog_collection');
        $this->_collection
            ->setStoreFilter(Mage::app()->getStore()->getId(), true)
            ->addFieldToFilter('main_table.status', 1) //only active entities

            ->addStatusFilter(Zozoconcepts_Blog_Model_Blog_Comment::STATUS_APPROVED) //only approved comments
            ->addCustomerFilter(Mage::getSingleton('customer/session')->getCustomerId()) //only my comments
            ->setDateOrder();
    }

    /**
     * Gets collection items count
     * @access public
     * @return int
     * @author Zozoconcepts Hybrid
     */
    public function count() {
        return $this->_collection->getSize();
    }

    /**
     * Get html code for toolbar
     * @access public
     * @return string
     * @author Zozoconcepts Hybrid
     */
    public function getToolbarHtml() {
        return $this->getChildHtml('toolbar');
    }

    /**
     * Initializes toolbar
     * @access protected
     * @return Mage_Core_Block_Abstract
     * @author Zozoconcepts Hybrid
     */
    protected function _prepareLayout()
    {
        $toolbar = $this->getLayout()->createBlock('page/html_pager', 'customer_blog_comments.toolbar')
            ->setCollection($this->getCollection());

        $this->setChild('toolbar', $toolbar);
        return parent::_prepareLayout();
    }

    /**
     * Get collection
     * @access protected
     * @return Zozoconcepts_Blog_Model_Resource_Blog_Comment_Blog_Collection
     * @author Zozoconcepts Hybrid
     */
    protected function _getCollection() {
        return $this->_collection;
    }

    /**
     * Get collection
     * @access public
     * @return Zozoconcepts_Blog_Model_Resource_Blog_Comment_Blog_Collection
     * @author Zozoconcepts Hybrid
     */
    public function getCollection() {
        return $this->_getCollection();
    }

    /**
     * Get review link
     * @access public
     * @param mixed $comment
     * @return string
     * @author Zozoconcepts Hybrid
     */
    public function getCommentLink($comment) {
        if ($comment instanceof Varien_Object){
            $comment = $comment->getCtCommentId();
        }
        return Mage::getUrl('zozoconcepts_blog/blog_customer_comment/view/', array('id'=>$comment));
    }

    /**
     * Get product link
     * @access public
     * @param mixed $comment
     * @return string
     * @author Zozoconcepts Hybrid
     */
    public function getBlogLink($comment) {
        return $comment->getBlogUrl();
    }

    /**
     * Format date in short format
     * @access public
     * @param $date
     * @return string
     * @author Zozoconcepts Hybrid
     */
    public function dateFormat($date) {
        return $this->formatDate($date, Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
    }
}
