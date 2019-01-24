<?php
/**
 * Zozoconcepts_Brands extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Zozoconcepts
 * @package        Zozoconcepts_Brands
 * @copyright      Copyright (c) 2014
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Brand front contrller
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Brands
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Brands_BrandController
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
         if (Mage::helper('zozoconcepts_brands/brand')->getUseBreadcrumbs()){
             if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')){
                 $breadcrumbBlock->addCrumb('home', array(
                            'label'    => Mage::helper('zozoconcepts_brands')->__('Home'),
                            'link'     => Mage::getUrl(),
                        )
                 );
                 $breadcrumbBlock->addCrumb('brands', array(
                            'label'    => Mage::helper('zozoconcepts_brands')->__('Brands'),
                            'link'    => '',
                    )
                 );
             }
         }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->setTitle(Mage::getStoreConfig('zozoconcepts_brands/brand/meta_title'));
            $headBlock->setKeywords(Mage::getStoreConfig('zozoconcepts_brands/brand/meta_keywords'));
            $headBlock->setDescription(Mage::getStoreConfig('zozoconcepts_brands/brand/meta_description'));
        }
        $this->renderLayout();
    }
    /**
     * init Brand
     * @access protected
     * @return Zozoconcepts_Brands_Model_Entity
     * @author Zozoconcepts Hybrid
     */
    protected function _initBrand(){
        $brandId   = $this->getRequest()->getParam('id', 0);
        $brand     = Mage::getModel('zozoconcepts_brands/brand')
                        ->setStoreId(Mage::app()->getStore()->getId())
                        ->load($brandId);
        if (!$brand->getId()){
            return false;
        }
        elseif (!$brand->getStatus()){
            return false;
        }
        return $brand;
    }
    /**
      * view brand action
      * @access public
      * @return void
      * @author Zozoconcepts Hybrid
      */
    public function viewAction(){
        $brand = $this->_initBrand();
        if (!$brand) {
            $this->_forward('no-route');
            return;
        }
        Mage::register('current_brand', $brand);
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($root = $this->getLayout()->getBlock('root')) {
            $root->addBodyClass('brands-brand brands-brand' . $brand->getId());
        }
        if (Mage::helper('zozoconcepts_brands/brand')->getUseBreadcrumbs()){
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')){
                $breadcrumbBlock->addCrumb('home', array(
                            'label'    => Mage::helper('zozoconcepts_brands')->__('Home'),
                            'link'     => Mage::getUrl(),
                        )
                );
                $breadcrumbBlock->addCrumb('brands', array(
                            'label'    => Mage::helper('zozoconcepts_brands')->__('Brands'),
                            'link'    => Mage::helper('zozoconcepts_brands/brand')->getBrandsUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb('brand', array(
                            'label'    => $brand->getTitle(),
                            'link'    => '',
                    )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            if ($brand->getMetaTitle()){
                $headBlock->setTitle($brand->getMetaTitle());
            }
            else{
                $headBlock->setTitle($brand->getTitle());
            }
            $headBlock->setKeywords($brand->getMetaKeywords());
            $headBlock->setDescription($brand->getMetaDescription());
        }
        $this->renderLayout();
    }
}
