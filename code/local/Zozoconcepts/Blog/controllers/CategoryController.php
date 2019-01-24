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
 * Category front contrller
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Blog
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Blog_CategoryController
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
         if (Mage::helper('zozoconcepts_blog/category')->getUseBreadcrumbs()){
             if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')){
                 $breadcrumbBlock->addCrumb('home', array(
                            'label'    => Mage::helper('zozoconcepts_blog')->__('Home'),
                            'link'     => Mage::getUrl(),
                        )
                 );
                 $breadcrumbBlock->addCrumb('categories', array(
                            'label'    => Mage::helper('zozoconcepts_blog')->__('Categories'),
                            'link'    => '',
                    )
                 );
             }
         }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->setTitle(Mage::getStoreConfig('zozoconcepts_blog/category/meta_title'));
            $headBlock->setKeywords(Mage::getStoreConfig('zozoconcepts_blog/category/meta_keywords'));
            $headBlock->setDescription(Mage::getStoreConfig('zozoconcepts_blog/category/meta_description'));
        }
        $this->renderLayout();
    }
    /**
     * init Category
     * @access protected
     * @return Zozoconcepts_Blog_Model_Entity
     * @author Zozoconcepts Hybrid
     */
    protected function _initCategory(){
        $categoryId   = $this->getRequest()->getParam('id', 0);
        $category     = Mage::getModel('zozoconcepts_blog/category')
                        ->setStoreId(Mage::app()->getStore()->getId())
                        ->load($categoryId);
        if (!$category->getId()){
            return false;
        }
        elseif (!$category->getStatus()){
            return false;
        }
        return $category;
    }
    /**
      * view category action
      * @access public
      * @return void
      * @author Zozoconcepts Hybrid
      */
    public function viewAction(){
        $category = $this->_initCategory();
        if (!$category) {
            $this->_forward('no-route');
            return;
        }
        Mage::register('current_category', $category);
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($root = $this->getLayout()->getBlock('root')) {
            $root->addBodyClass('blog-category blog-category' . $category->getId());
        }
        if (Mage::helper('zozoconcepts_blog/category')->getUseBreadcrumbs()){
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')){
                $breadcrumbBlock->addCrumb('home', array(
                            'label'    => Mage::helper('zozoconcepts_blog')->__('Home'),
                            'link'     => Mage::getUrl(),
                        )
                );
                $breadcrumbBlock->addCrumb('categories', array(
                            'label'    => Mage::helper('zozoconcepts_blog')->__('Categories'),
                            'link'    => Mage::helper('zozoconcepts_blog/category')->getCategoriesUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb('category', array(
                            'label'    => $category->getCatName(),
                            'link'    => '',
                    )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            if ($category->getMetaTitle()){
                $headBlock->setTitle($category->getMetaTitle());
            }
            else{
                $headBlock->setTitle($category->getCatName());
            }
            $headBlock->setKeywords($category->getMetaKeywords());
            $headBlock->setDescription($category->getMetaDescription());
        }
        $this->renderLayout();
    }
    /**
     * categories rss list action
     * @access public
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function rssAction(){
        if (Mage::helper('zozoconcepts_blog/category')->isRssEnabled()) {
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
}
