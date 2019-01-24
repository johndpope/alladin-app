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
 * Brand admin controller
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Brands
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Brands_Adminhtml_Brands_BrandController
    extends Zozoconcepts_Brands_Controller_Adminhtml_Brands {
    /**
     * init the brand
     * @access protected
     * @return Zozoconcepts_Brands_Model_Brand
     */
    protected function _initBrand(){
        $brandId  = (int) $this->getRequest()->getParam('id');
        $brand    = Mage::getModel('zozoconcepts_brands/brand');
        if ($brandId) {
            $brand->load($brandId);
        }
        Mage::register('current_brand', $brand);
        return $brand;
    }
     /**
     * default action
     * @access public
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function indexAction() {
        $this->loadLayout();
        $this->_title(Mage::helper('zozoconcepts_brands')->__('Zozoconcepts'))
             ->_title(Mage::helper('zozoconcepts_brands')->__('Brands'));
        $this->renderLayout();
    }
    /**
     * grid action
     * @access public
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function gridAction() {
        $this->loadLayout()->renderLayout();
    }
    /**
     * edit brand - action
     * @access public
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function editAction() {
        $brandId    = $this->getRequest()->getParam('id');
        $brand      = $this->_initBrand();
        if ($brandId && !$brand->getId()) {
            $this->_getSession()->addError(Mage::helper('zozoconcepts_brands')->__('This brand no longer exists.'));
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getBrandData(true);
        if (!empty($data)) {
            $brand->setData($data);
        }
        Mage::register('brand_data', $brand);
        $this->loadLayout();
        $this->_title(Mage::helper('zozoconcepts_brands')->__('Zozoconcepts'))
             ->_title(Mage::helper('zozoconcepts_brands')->__('Brands'));
        if ($brand->getId()){
            $this->_title($brand->getTitle());
        }
        else{
            $this->_title(Mage::helper('zozoconcepts_brands')->__('Add brand'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }
    /**
     * new brand action
     * @access public
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function newAction() {
        $this->_forward('edit');
    }
    /**
     * save brand - action
     * @access public
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function saveAction() {
        if ($data = $this->getRequest()->getPost('brand')) {
            try {
                $brand = $this->_initBrand();
                $brand->addData($data);
                $brandIconName = $this->_uploadAndGetName('brand_icon', Mage::helper('zozoconcepts_brands/brand_image')->getImageBaseDir(), $data);
                $brand->setData('brand_icon', $brandIconName);
                $brandImageName = $this->_uploadAndGetName('brand_image', Mage::helper('zozoconcepts_brands/brand_image')->getImageBaseDir(), $data);
                $brand->setData('brand_image', $brandImageName);
                $verifiedOwnershipsName = $this->_uploadAndGetName('verified_ownerships', Mage::helper('zozoconcepts_brands/brand')->getFileBaseDir(), $data);
                $brand->setData('verified_ownerships', $verifiedOwnershipsName);
                $products = $this->getRequest()->getPost('products', -1);
                if ($products != -1) {
                    $brand->setProductsData(Mage::helper('adminhtml/js')->decodeGridSerializedInput($products));
                }
                $brand->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('zozoconcepts_brands')->__('Brand was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $brand->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            }
            catch (Mage_Core_Exception $e){
                if (isset($data['brand_icon']['value'])){
                    $data['brand_icon'] = $data['brand_icon']['value'];
                }
                if (isset($data['brand_image']['value'])){
                    $data['brand_image'] = $data['brand_image']['value'];
                }
                if (isset($data['verified_ownerships']['value'])){
                    $data['verified_ownerships'] = $data['verified_ownerships']['value'];
                }
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setBrandData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
            catch (Exception $e) {
                Mage::logException($e);
                if (isset($data['brand_icon']['value'])){
                    $data['brand_icon'] = $data['brand_icon']['value'];
                }
                if (isset($data['brand_image']['value'])){
                    $data['brand_image'] = $data['brand_image']['value'];
                }
                if (isset($data['verified_ownerships']['value'])){
                    $data['verified_ownerships'] = $data['verified_ownerships']['value'];
                }
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('zozoconcepts_brands')->__('There was a problem saving the brand.'));
                Mage::getSingleton('adminhtml/session')->setBrandData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('zozoconcepts_brands')->__('Unable to find brand to save.'));
        $this->_redirect('*/*/');
    }
    /**
     * delete brand - action
     * @access public
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function deleteAction() {
        if( $this->getRequest()->getParam('id') > 0) {
            try {
                $brand = Mage::getModel('zozoconcepts_brands/brand');
                $brand->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('zozoconcepts_brands')->__('Brand was successfully deleted.'));
                $this->_redirect('*/*/');
                return;
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('zozoconcepts_brands')->__('There was an error deleting brand.'));
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('zozoconcepts_brands')->__('Could not find brand to delete.'));
        $this->_redirect('*/*/');
    }
    /**
     * mass delete brand - action
     * @access public
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function massDeleteAction() {
        $brandIds = $this->getRequest()->getParam('brand');
        if(!is_array($brandIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('zozoconcepts_brands')->__('Please select brands to delete.'));
        }
        else {
            try {
                foreach ($brandIds as $brandId) {
                    $brand = Mage::getModel('zozoconcepts_brands/brand');
                    $brand->setId($brandId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('zozoconcepts_brands')->__('Total of %d brands were successfully deleted.', count($brandIds)));
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('zozoconcepts_brands')->__('There was an error deleting brands.'));
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }
    /**
     * mass status change - action
     * @access public
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function massStatusAction(){
        $brandIds = $this->getRequest()->getParam('brand');
        if(!is_array($brandIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('zozoconcepts_brands')->__('Please select brands.'));
        }
        else {
            try {
                foreach ($brandIds as $brandId) {
                $brand = Mage::getSingleton('zozoconcepts_brands/brand')->load($brandId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess($this->__('Total of %d brands were successfully updated.', count($brandIds)));
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('zozoconcepts_brands')->__('There was an error updating brands.'));
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }
    /**
     * mass Featured Brands change - action
     * @access public
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function massFeaturedBrandsAction(){
        $brandIds = $this->getRequest()->getParam('brand');
        if(!is_array($brandIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('zozoconcepts_brands')->__('Please select brands.'));
        }
        else {
            try {
                foreach ($brandIds as $brandId) {
                $brand = Mage::getSingleton('zozoconcepts_brands/brand')->load($brandId)
                            ->setFeaturedBrands($this->getRequest()->getParam('flag_featured_brands'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess($this->__('Total of %d brands were successfully updated.', count($brandIds)));
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('zozoconcepts_brands')->__('There was an error updating brands.'));
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }
    /**
     * get grid of products action
     * @access public
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function productsAction(){
        $this->_initBrand();
        $this->loadLayout();
        $this->getLayout()->getBlock('brand.edit.tab.product')
            ->setBrandProducts($this->getRequest()->getPost('brand_products', null));
        $this->renderLayout();
    }
    /**
     * get grid of products action
     * @access public
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function productsgridAction(){
        $this->_initBrand();
        $this->loadLayout();
        $this->getLayout()->getBlock('brand.edit.tab.product')
            ->setBrandProducts($this->getRequest()->getPost('brand_products', null));
        $this->renderLayout();
    }
    /**
     * export as csv - action
     * @access public
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function exportCsvAction(){
        $fileName   = 'brand.csv';
        $content    = $this->getLayout()->createBlock('zozoconcepts_brands/adminhtml_brand_grid')->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    /**
     * export as MsExcel - action
     * @access public
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function exportExcelAction(){
        $fileName   = 'brand.xls';
        $content    = $this->getLayout()->createBlock('zozoconcepts_brands/adminhtml_brand_grid')->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    /**
     * export as xml - action
     * @access public
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function exportXmlAction(){
        $fileName   = 'brand.xml';
        $content    = $this->getLayout()->createBlock('zozoconcepts_brands/adminhtml_brand_grid')->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    /**
     * Check if admin has permissions to visit related pages
     * @access protected
     * @return boolean
     * @author Zozoconcepts Hybrid
     */
    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('zozoconcepts_brands/brand');
    }
}
