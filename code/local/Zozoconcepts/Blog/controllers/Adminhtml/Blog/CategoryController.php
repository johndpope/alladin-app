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
 * Category admin controller
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Blog
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Blog_Adminhtml_Blog_CategoryController
    extends Zozoconcepts_Blog_Controller_Adminhtml_Blog {
    /**
     * init the category
     * @access protected
     * @return Zozoconcepts_Blog_Model_Category
     */
    protected function _initCategory(){
        $categoryId  = (int) $this->getRequest()->getParam('id');
        $category    = Mage::getModel('zozoconcepts_blog/category');
        if ($categoryId) {
            $category->load($categoryId);
        }
        Mage::register('current_category', $category);
        return $category;
    }
     /**
     * default action
     * @access public
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function indexAction() {
        $this->loadLayout();
        $this->_title(Mage::helper('zozoconcepts_blog')->__('Zozoconcepts'))
             ->_title(Mage::helper('zozoconcepts_blog')->__('Categories'));
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
     * edit category - action
     * @access public
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function editAction() {
        $categoryId    = $this->getRequest()->getParam('id');
        $category      = $this->_initCategory();
        if ($categoryId && !$category->getId()) {
            $this->_getSession()->addError(Mage::helper('zozoconcepts_blog')->__('This category no longer exists.'));
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getCategoryData(true);
        if (!empty($data)) {
            $category->setData($data);
        }
        Mage::register('category_data', $category);
        $this->loadLayout();
        $this->_title(Mage::helper('zozoconcepts_blog')->__('Zozoconcepts'))
             ->_title(Mage::helper('zozoconcepts_blog')->__('Categories'));
        if ($category->getId()){
            $this->_title($category->getCatName());
        }
        else{
            $this->_title(Mage::helper('zozoconcepts_blog')->__('Add category'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }
    /**
     * new category action
     * @access public
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function newAction() {
        $this->_forward('edit');
    }
    /**
     * save category - action
     * @access public
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function saveAction() {
        if ($data = $this->getRequest()->getPost('category')) {
            try {
                $category = $this->_initCategory();
                $category->addData($data);
                $category->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('zozoconcepts_blog')->__('Category was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $category->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setCategoryData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
            catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('zozoconcepts_blog')->__('There was a problem saving the category.'));
                Mage::getSingleton('adminhtml/session')->setCategoryData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('zozoconcepts_blog')->__('Unable to find category to save.'));
        $this->_redirect('*/*/');
    }
    /**
     * delete category - action
     * @access public
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function deleteAction() {
        if( $this->getRequest()->getParam('id') > 0) {
            try {
                $category = Mage::getModel('zozoconcepts_blog/category');
                $category->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('zozoconcepts_blog')->__('Category was successfully deleted.'));
                $this->_redirect('*/*/');
                return;
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('zozoconcepts_blog')->__('There was an error deleting category.'));
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('zozoconcepts_blog')->__('Could not find category to delete.'));
        $this->_redirect('*/*/');
    }
    /**
     * mass delete category - action
     * @access public
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function massDeleteAction() {
        $categoryIds = $this->getRequest()->getParam('category');
        if(!is_array($categoryIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('zozoconcepts_blog')->__('Please select categories to delete.'));
        }
        else {
            try {
                foreach ($categoryIds as $categoryId) {
                    $category = Mage::getModel('zozoconcepts_blog/category');
                    $category->setId($categoryId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('zozoconcepts_blog')->__('Total of %d categories were successfully deleted.', count($categoryIds)));
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('zozoconcepts_blog')->__('There was an error deleting categories.'));
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
        $categoryIds = $this->getRequest()->getParam('category');
        if(!is_array($categoryIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('zozoconcepts_blog')->__('Please select categories.'));
        }
        else {
            try {
                foreach ($categoryIds as $categoryId) {
                $category = Mage::getSingleton('zozoconcepts_blog/category')->load($categoryId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess($this->__('Total of %d categories were successfully updated.', count($categoryIds)));
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('zozoconcepts_blog')->__('There was an error updating categories.'));
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }
    /**
     * export as csv - action
     * @access public
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function exportCsvAction(){
        $fileName   = 'category.csv';
        $content    = $this->getLayout()->createBlock('zozoconcepts_blog/adminhtml_category_grid')->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    /**
     * export as MsExcel - action
     * @access public
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function exportExcelAction(){
        $fileName   = 'category.xls';
        $content    = $this->getLayout()->createBlock('zozoconcepts_blog/adminhtml_category_grid')->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    /**
     * export as xml - action
     * @access public
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function exportXmlAction(){
        $fileName   = 'category.xml';
        $content    = $this->getLayout()->createBlock('zozoconcepts_blog/adminhtml_category_grid')->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    /**
     * Check if admin has permissions to visit related pages
     * @access protected
     * @return boolean
     * @author Zozoconcepts Hybrid
     */
    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('zozoconcepts_blog/category');
    }
}
