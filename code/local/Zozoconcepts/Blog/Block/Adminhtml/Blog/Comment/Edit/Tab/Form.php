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
 * Blog comment edit form tab
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Blog
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Blog_Block_Adminhtml_Blog_Comment_Edit_Tab_Form
    extends Mage_Adminhtml_Block_Widget_Form {
    /**
     * prepare the form
     * @access protected
     * @return Blog_Blog_Block_Adminhtml_Blog_Comment_Edit_Tab_Form
     * @author Zozoconcepts Hybrid
     */
    protected function _prepareForm(){
        $blog = Mage::registry('current_blog');
        $comment    = Mage::registry('current_comment');
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('comment_');
        $form->setFieldNameSuffix('comment');
        $this->setForm($form);
        $fieldset = $form->addFieldset('comment_form', array('legend'=>Mage::helper('zozoconcepts_blog')->__('Comment')));
        $fieldset->addField('blog_id', 'hidden', array(
            'name'  => 'blog_id',
            'after_element_html' => '<a href="'.Mage::helper('adminhtml')->getUrl('adminhtml/blog_blog/edit', array('id'=>$blog->getId())).'" target="_blank">'.Mage::helper('zozoconcepts_blog')->__('Blog').' : '.$blog->getTitle().'</a>'
        ));
        $fieldset->addField('title', 'text', array(
            'label' => Mage::helper('zozoconcepts_blog')->__('Title'),
            'name'  => 'title',
            'required'  => true,
            'class' => 'required-entry',
        ));
        $fieldset->addField('comment', 'textarea', array(
            'label' => Mage::helper('zozoconcepts_blog')->__('Comment'),
            'name'  => 'comment',
            'required'  => true,
            'class' => 'required-entry',
        ));
        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('zozoconcepts_blog')->__('Status'),
            'name'  => 'status',
            'required'  => true,
            'class' => 'required-entry',
            'values'=> array(
                array(
                    'value' => Zozoconcepts_Blog_Model_Blog_Comment::STATUS_PENDING,
                    'label' => Mage::helper('zozoconcepts_blog')->__('Pending'),
                ),
                array(
                    'value' => Zozoconcepts_Blog_Model_Blog_Comment::STATUS_APPROVED,
                    'label' => Mage::helper('zozoconcepts_blog')->__('Approved'),
                ),
                array(
                    'value' => Zozoconcepts_Blog_Model_Blog_Comment::STATUS_REJECTED,
                    'label' => Mage::helper('zozoconcepts_blog')->__('Rejected'),
                ),
            ),
        ));
        $configuration = array(
             'label' => Mage::helper('zozoconcepts_blog')->__('Poster name'),
             'name'  => 'name',
             'required'  => true,
             'class' => 'required-entry',
        );
        if ($comment->getCustomerId()) {
            $configuration['after_element_html'] = '<a href="'.Mage::helper('adminhtml')->getUrl('adminhtml/customer/edit', array('id'=>$comment->getCustomerId())).'" target="_blank">'.Mage::helper('zozoconcepts_blog')->__('Customer profile').'</a>';
        }
        $fieldset->addField('name', 'text', $configuration);
        $fieldset->addField('email', 'text', array(
            'label' => Mage::helper('zozoconcepts_blog')->__('Poster e-mail'),
            'name'  => 'email',
            'required'  => true,
            'class' => 'required-entry',
        ));
        $fieldset->addField('customer_id', 'hidden', array(
            'name'  => 'customer_id',
        ));

        if (Mage::app()->isSingleStoreMode()){
            $fieldset->addField('store_id', 'hidden', array(
                'name'      => 'stores[]',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
            Mage::registry('current_comment')->setStoreId(Mage::app()->getStore(true)->getId());
        }
        $form->addValues($this->getComment()->getData());
        return parent::_prepareForm();
    }
    /**
     * get the current comment
     * @access public
     * @return Zozoconcepts_Blog_Model_Blog_Comment
     */
    public function getComment(){
        return Mage::registry('current_comment');
    }
}