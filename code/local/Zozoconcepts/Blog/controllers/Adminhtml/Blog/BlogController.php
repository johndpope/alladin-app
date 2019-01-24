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
 * Blog admin controller
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Blog
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Blog_Adminhtml_Blog_BlogController
    extends Zozoconcepts_Blog_Controller_Adminhtml_Blog {
    /**
     * init the blog
     * @access protected
     * @return Zozoconcepts_Blog_Model_Blog
     */
    protected function _initBlog(){
        $blogId  = (int) $this->getRequest()->getParam('id');
        $blog    = Mage::getModel('zozoconcepts_blog/blog');
        if ($blogId) {
            $blog->load($blogId);
        }
        Mage::register('current_blog', $blog);
        return $blog;
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
             ->_title(Mage::helper('zozoconcepts_blog')->__('Posts'));
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
     * edit blog - action
     * @access public
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function editAction() {
        $blogId    = $this->getRequest()->getParam('id');
        $blog      = $this->_initBlog();
        if ($blogId && !$blog->getId()) {
            $this->_getSession()->addError(Mage::helper('zozoconcepts_blog')->__('This post no longer exists.'));
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getBlogData(true);
        if (!empty($data)) {
            $blog->setData($data);
        }
        Mage::register('blog_data', $blog);
        $this->loadLayout();
        $this->_title(Mage::helper('zozoconcepts_blog')->__('Zozoconcepts'))
             ->_title(Mage::helper('zozoconcepts_blog')->__('Posts'));
        if ($blog->getId()){
            $this->_title($blog->getTitle());
        }
        else{
            $this->_title(Mage::helper('zozoconcepts_blog')->__('Add post'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }
    /**
     * new blog action
     * @access public
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function newAction() {
        $this->_forward('edit');
    }
    /**
     * save blog - action
     * @access public
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function saveAction() {
        if ($data = $this->getRequest()->getPost('blog')) {
			
            try {
                $blog = $this->_initBlog();
                $blog->addData($data);
                $featuredImageName = $this->_uploadAndGetName('featured_image', Mage::helper('zozoconcepts_blog/blog_image')->getImageBaseDir(), $data);
                $blog->setData('featured_image', $featuredImageName);
                $blog->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('zozoconcepts_blog')->__('Post was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $blog->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            }
            catch (Mage_Core_Exception $e){
                if (isset($data['featured_image']['value'])){
                    $data['featured_image'] = $data['featured_image']['value'];
                }
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setBlogData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
            catch (Exception $e) {
                Mage::logException($e);
                if (isset($data['featured_image']['value'])){
                    $data['featured_image'] = $data['featured_image']['value'];
                }
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('zozoconcepts_blog')->__('There was a problem saving the Post.'));
                Mage::getSingleton('adminhtml/session')->setBlogData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('zozoconcepts_blog')->__('Unable to find Post to save.'));
        $this->_redirect('*/*/');
    }
    /**
     * delete blog - action
     * @access public
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function deleteAction() {
        if( $this->getRequest()->getParam('id') > 0) {
            try {
                $blog = Mage::getModel('zozoconcepts_blog/blog');
                $blog->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('zozoconcepts_blog')->__('Blog was successfully deleted.'));
                $this->_redirect('*/*/');
                return;
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('zozoconcepts_blog')->__('There was an error deleting blog.'));
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('zozoconcepts_blog')->__('Could not find blog to delete.'));
        $this->_redirect('*/*/');
    }
    /**
     * mass delete blog - action
     * @access public
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function massDeleteAction() {
        $blogIds = $this->getRequest()->getParam('blog');
        if(!is_array($blogIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('zozoconcepts_blog')->__('Please select blogs to delete.'));
        }
        else {
            try {
                foreach ($blogIds as $blogId) {
                    $blog = Mage::getModel('zozoconcepts_blog/blog');
                    $blog->setId($blogId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('zozoconcepts_blog')->__('Total of %d blogs were successfully deleted.', count($blogIds)));
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('zozoconcepts_blog')->__('There was an error deleting blogs.'));
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
        $blogIds = $this->getRequest()->getParam('blog');
        if(!is_array($blogIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('zozoconcepts_blog')->__('Please select blogs.'));
        }
        else {
            try {
                foreach ($blogIds as $blogId) {
                $blog = Mage::getSingleton('zozoconcepts_blog/blog')->load($blogId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess($this->__('Total of %d blogs were successfully updated.', count($blogIds)));
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('zozoconcepts_blog')->__('There was an error updating blogs.'));
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }
    /**
     * mass Show on Slider change - action
     * @access public
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function massShowOnslideAction(){
        $blogIds = $this->getRequest()->getParam('blog');
        if(!is_array($blogIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('zozoconcepts_blog')->__('Please select blogs.'));
        }
        else {
            try {
                foreach ($blogIds as $blogId) {
                $blog = Mage::getSingleton('zozoconcepts_blog/blog')->load($blogId)
                            ->setShowOnslide($this->getRequest()->getParam('flag_show_onslide'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess($this->__('Total of %d blogs were successfully updated.', count($blogIds)));
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('zozoconcepts_blog')->__('There was an error updating blogs.'));
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }
    /**
     * mass category change - action
     * @access public
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function massCategoryIdAction(){
        $blogIds = $this->getRequest()->getParam('blog');
        if(!is_array($blogIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('zozoconcepts_blog')->__('Please select blogs.'));
        }
        else {
            try {
                foreach ($blogIds as $blogId) {
                $blog = Mage::getSingleton('zozoconcepts_blog/blog')->load($blogId)
                            ->setCategoryId($this->getRequest()->getParam('flag_category_id'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess($this->__('Total of %d blogs were successfully updated.', count($blogIds)));
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('zozoconcepts_blog')->__('There was an error updating blogs.'));
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
        $fileName   = 'blog.csv';
        $content    = $this->getLayout()->createBlock('zozoconcepts_blog/adminhtml_blog_grid')->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    /**
     * export as MsExcel - action
     * @access public
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function exportExcelAction(){
        $fileName   = 'blog.xls';
        $content    = $this->getLayout()->createBlock('zozoconcepts_blog/adminhtml_blog_grid')->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    /**
     * export as xml - action
     * @access public
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function exportXmlAction(){
        $fileName   = 'blog.xml';
        $content    = $this->getLayout()->createBlock('zozoconcepts_blog/adminhtml_blog_grid')->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    /**
     * Check if admin has permissions to visit related pages
     * @access protected
     * @return boolean
     * @author Zozoconcepts Hybrid
     */
    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('zozoconcepts_blog/blog');
    }
}
