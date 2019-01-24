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
 * Blog front contrller
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Blog
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Blog_BlogController
    extends Mage_Core_Controller_Front_Action {
    /**
      * default action
      * @access public
      * @return void
      * @author Zozoconcepts Hybrid
      */
    public function indexAction(){
         $this->loadLayout();
         $this->_initLayoutMessages('catalog/session');
         $this->_initLayoutMessages('customer/session');
         $this->_initLayoutMessages('checkout/session');
         if (Mage::helper('zozoconcepts_blog/blog')->getUseBreadcrumbs()){
             if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')){
                 $breadcrumbBlock->addCrumb('home', array(
                            'label'    => Mage::helper('zozoconcepts_blog')->__('Home'),
                            'link'     => Mage::getUrl(),
                        )
                 );
                 $breadcrumbBlock->addCrumb('blogs', array(
                            'label'    => Mage::helper('zozoconcepts_blog')->__('Blogs'),
                            'link'    => '',
                    )
                 );
             }
         }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->setTitle(Mage::getStoreConfig('zozoconcepts_blog/blog/meta_title'));
            $headBlock->setKeywords(Mage::getStoreConfig('zozoconcepts_blog/blog/meta_keywords'));
            $headBlock->setDescription(Mage::getStoreConfig('zozoconcepts_blog/blog/meta_description'));
        }
        $this->renderLayout();
    }
    /**
     * init Blog
     * @access protected
     * @return Zozoconcepts_Blog_Model_Entity
     * @author Zozoconcepts Hybrid
     */
    protected function _initBlog(){
        $blogId   = $this->getRequest()->getParam('id', 0);
        $blog     = Mage::getModel('zozoconcepts_blog/blog')
                        ->setStoreId(Mage::app()->getStore()->getId())
                        ->load($blogId);
        if (!$blog->getId()){
            return false;
        }
        elseif (!$blog->getStatus()){
            return false;
        }
        return $blog;
    }
    /**
      * view blog action
      * @access public
      * @return void
      * @author Zozoconcepts Hybrid
      */
    public function viewAction(){
        $blog = $this->_initBlog();
        if (!$blog) {
            $this->_forward('no-route');
            return;
        }
        Mage::register('current_blog', $blog);
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($root = $this->getLayout()->getBlock('root')) {
            $root->addBodyClass('blog-blog blog-blog' . $blog->getId());
        }
        if (Mage::helper('zozoconcepts_blog/blog')->getUseBreadcrumbs()){
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')){
                $breadcrumbBlock->addCrumb('home', array(
                            'label'    => Mage::helper('zozoconcepts_blog')->__('Home'),
                            'link'     => Mage::getUrl(),
                        )
                );
                $breadcrumbBlock->addCrumb('blogs', array(
                            'label'    => Mage::helper('zozoconcepts_blog')->__('Blogs'),
                            'link'    => Mage::helper('zozoconcepts_blog/blog')->getBlogsUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb('blog', array(
                            'label'    => $blog->getTitle(),
                            'link'    => '',
                    )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            if ($blog->getMetaTitle()){
                $headBlock->setTitle($blog->getMetaTitle());
            }
            else{
                $headBlock->setTitle($blog->getTitle());
            }
            $headBlock->setKeywords($blog->getMetaKeywords());
            $headBlock->setDescription($blog->getMetaDescription());
        }
        $this->renderLayout();
    }
    /**
     * blogs rss list action
     * @access public
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function rssAction(){
        if (Mage::helper('zozoconcepts_blog/blog')->isRssEnabled()) {
            $this->getResponse()->setHeader('Content-type', 'text/xml; charset=UTF-8');
            $this->loadLayout(false);
            $this->renderLayout();
        }
        else {
            $this->getResponse()->setHeader('HTTP/1.1','404 Not Found');
            $this->getResponse()->setHeader('Status','404 File not found');
            $this->_forward('nofeed','index','rss');
        }
    }
    /**
     * Submit new comment action
     *
     */
    public function commentpostAction() {
        $data   = $this->getRequest()->getPost();
        $blog = $this->_initBlog();
        $session    = Mage::getSingleton('core/session');
        if ($blog) {
            if ($blog->getAllowComments()) {
                if ((Mage::getSingleton('customer/session')->isLoggedIn() || Mage::getStoreConfigFlag('zozoconcepts_blog/blog/allow_guest_comment'))){
                    $comment    = Mage::getModel('zozoconcepts_blog/blog_comment')->setData($data);
                    $validate = $comment->validate();
                    if ($validate === true) {
                        try {
                            $comment->setBlogId($blog->getId())
                                ->setStatus(Zozoconcepts_Blog_Model_Blog_Comment::STATUS_PENDING)
                                ->setCustomerId(Mage::getSingleton('customer/session')->getCustomerId())
                                ->setStores(array(Mage::app()->getStore()->getId()))
                                ->save();
                            $session->addSuccess($this->__('Your comment has been accepted for moderation.'));
                        }
                        catch (Exception $e) {
                            $session->setBlogCommentData($data);
                            $session->addError($this->__('Unable to post the comment.'));
                        }
                    }
                    else {
                        $session->setBlogCommentData($data);
                        if (is_array($validate)) {
                            foreach ($validate as $errorMessage) {
                                $session->addError($errorMessage);
                            }
                        }
                        else {
                            $session->addError($this->__('Unable to post the comment.'));
                        }
                    }
                }
                else {
                    $session->addError($this->__('Guest comments are not allowed'));
                }
            }
            else {
                $session->addError($this->__('This blog does not allow comments'));
            }
        }
        $this->_redirectReferer();
    }
}
