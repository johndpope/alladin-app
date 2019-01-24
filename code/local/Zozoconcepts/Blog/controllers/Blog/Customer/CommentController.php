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
 * Blog comments controller
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Blog
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Blog_Blog_Customer_CommentController
    extends Mage_Core_Controller_Front_Action {
    /**
     * Action predispatch
     * Check customer authentication for some actions
     * @access public
     * @author Zozoconcepts Hybrid
     */
    public function preDispatch() {
        parent::preDispatch();
        if (!Mage::getSingleton('customer/session')->authenticate($this)) {
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
        }
    }
    /**
     * List comments
     * @access public
     * @author Zozoconcepts Hybrid
     */
    public function indexAction() {
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($navigationBlock = $this->getLayout()->getBlock('customer_account_navigation')) {
            $navigationBlock->setActive('zozoconcepts_blog/blog_customer_comment/');
        }
        if ($block = $this->getLayout()->getBlock('blog_customer_comment_list')) {
            $block->setRefererUrl($this->_getRefererUrl());
        }

        $this->getLayout()->getBlock('head')->setTitle($this->__('My Blog Comments'));

        $this->renderLayout();
    }
    /**
     * View comment
     * @access public
     * @author Zozoconcepts Hybrid
     */
    public function viewAction() {
        $commentId = $this->getRequest()->getParam('id');
        $comment = Mage::getModel('zozoconcepts_blog/blog_comment')->load($commentId);
        if (!$comment->getId() || $comment->getCustomerId() != Mage::getSingleton('customer/session')->getCustomerId() || $comment->getStatus() != Zozoconcepts_Blog_Model_Blog_Comment::STATUS_APPROVED) {
            $this->_forward('no-route');
            return;
        }
        $blog = Mage::getModel('zozoconcepts_blog/blog')
                ->load($comment->getBlogId());
        if (!$blog->getId() || $blog->getStatus() != 1){
            $this->_forward('no-route');
            return;
        }
        $stores = array(Mage::app()->getStore()->getId(), 0);
        if (count(array_intersect($stores, $comment->getStoreId())) == 0) {
            $this->_forward('no-route');
            return;
        }
        if (count(array_intersect($stores, $blog->getStoreId())) == 0) {
            $this->_forward('no-route');
            return;
        }
        Mage::register('current_comment', $comment);
        Mage::register('current_blog', $blog);
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($navigationBlock = $this->getLayout()->getBlock('customer_account_navigation')) {
            $navigationBlock->setActive('zozoconcepts_blog/blog_customer_comment/');
        }
        if ($block = $this->getLayout()->getBlock('customer_blog_comment')) {
            $block->setRefererUrl($this->_getRefererUrl());
        }
        $this->getLayout()->getBlock('head')->setTitle($this->__('My Blog Comments'));
        $this->renderLayout();
    }
}
