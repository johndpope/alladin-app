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
 * Blog edit form tab
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Blog
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Blog_Block_Adminhtml_Blog_Edit_Tab_Form
    extends Mage_Adminhtml_Block_Widget_Form {
    /**
     * prepare the form
     * @access protected
     * @return Blog_Blog_Block_Adminhtml_Blog_Edit_Tab_Form
     * @author Zozoconcepts Hybrid
     */
    protected function _prepareForm(){
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('blog_');
        $form->setFieldNameSuffix('blog');
        $this->setForm($form);
        $fieldset = $form->addFieldset('blog_form', array('legend'=>Mage::helper('zozoconcepts_blog')->__('Blog')));
        $fieldset->addType('image', Mage::getConfig()->getBlockClassName('zozoconcepts_blog/adminhtml_blog_helper_image'));
        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig();
        $values = Mage::getResourceModel('zozoconcepts_blog/category_collection')->toOptionArray();
        array_unshift($values, array('label'=>'', 'value'=>''));
        $fieldset->addField('category_id', 'select', array(
            'label'     => Mage::helper('zozoconcepts_blog')->__('Category'),
            'name'      => 'category_id',
            'required'  => false,
            'values'    => $values
        ));

        $fieldset->addField('title', 'text', array(
            'label' => Mage::helper('zozoconcepts_blog')->__('Title'),
            'name'  => 'title',
            'required'  => true,
            'class' => 'required-entry',

        ));

        $fieldset->addField('excerpt', 'editor', array(
            'label' => Mage::helper('zozoconcepts_blog')->__('Excerpt'),
            'name'  => 'excerpt',
            'config' => $wysiwygConfig,
            'note'	=> $this->__('Put your short excerpt here'),

        ));

        $fieldset->addField('full_description', 'editor', array(
            'label' => Mage::helper('zozoconcepts_blog')->__('Full Description'),
            'name'  => 'full_description',
            'config' => $wysiwygConfig,
            'required'  => true,
            'class' => 'required-entry',

        ));

        $fieldset->addField('featured_image', 'image', array(
            'label' => Mage::helper('zozoconcepts_blog')->__('Featured Image'),
            'name'  => 'featured_image',
            'note'	=> $this->__('This will be set as the Featured Post Image'),

        ));

        /*$fieldset->addField('show_onslide', 'select', array(
            'label' => Mage::helper('zozoconcepts_blog')->__('Show on Slider'),
            'name'  => 'show_onslide',
            'note'	=> $this->__('Enable this to show post on Featured Blog slider'),
            'required'  => true,
            'class' => 'required-entry',

            'values'=> array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('zozoconcepts_blog')->__('Yes'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('zozoconcepts_blog')->__('No'),
                ),
            ),
        ));*/
        $fieldset->addField('url_key', 'text', array(
            'label' => Mage::helper('zozoconcepts_blog')->__('Url key'),
            'name'  => 'url_key',
            'note'    => Mage::helper('zozoconcepts_blog')->__('Relative to Website Base URL')
        ));
        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('zozoconcepts_blog')->__('Status'),
            'name'  => 'status',
            'values'=> array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('zozoconcepts_blog')->__('Enabled'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('zozoconcepts_blog')->__('Disabled'),
                ),
            ),
        ));
        $fieldset->addField('in_rss', 'select', array(
            'label' => Mage::helper('zozoconcepts_blog')->__('Show in rss'),
            'name'  => 'in_rss',
            'values'=> array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('zozoconcepts_blog')->__('Yes'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('zozoconcepts_blog')->__('No'),
                ),
            ),
        ));
        if (Mage::app()->isSingleStoreMode()){
            $fieldset->addField('store_id', 'hidden', array(
                'name'      => 'stores[]',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
            Mage::registry('current_blog')->setStoreId(Mage::app()->getStore(true)->getId());
        }
        $fieldset->addField('allow_comment', 'select', array(
            'label' => Mage::helper('zozoconcepts_blog')->__('Allow Comments'),
            'name'  => 'allow_comment',
            'values'=> Mage::getModel('zozoconcepts_blog/adminhtml_source_yesnodefault')->toOptionArray()
        ));
        $formValues = Mage::registry('current_blog')->getDefaultValues();
        if (!is_array($formValues)){
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getBlogData()){
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getBlogData());
            Mage::getSingleton('adminhtml/session')->setBlogData(null);
        }
        elseif (Mage::registry('current_blog')){
            $formValues = array_merge($formValues, Mage::registry('current_blog')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
