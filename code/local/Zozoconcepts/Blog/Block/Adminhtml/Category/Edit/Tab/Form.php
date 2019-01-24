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
 * Category edit form tab
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Blog
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Blog_Block_Adminhtml_Category_Edit_Tab_Form
    extends Mage_Adminhtml_Block_Widget_Form {
    /**
     * prepare the form
     * @access protected
     * @return Blog_Category_Block_Adminhtml_Category_Edit_Tab_Form
     * @author Zozoconcepts Hybrid
     */
    protected function _prepareForm(){
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('category_');
        $form->setFieldNameSuffix('category');
        $this->setForm($form);
        $fieldset = $form->addFieldset('category_form', array('legend'=>Mage::helper('zozoconcepts_blog')->__('Category')));
        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig();

        $fieldset->addField('cat_name', 'text', array(
            'label' => Mage::helper('zozoconcepts_blog')->__('Name'),
            'name'  => 'cat_name',
            'required'  => true,
            'class' => 'required-entry',

        ));

        $fieldset->addField('cat_desc', 'editor', array(
            'label' => Mage::helper('zozoconcepts_blog')->__('Category Description'),
            'name'  => 'cat_desc',
            'config' => $wysiwygConfig,
            'required'  => true,
            'class' => 'required-entry',

        ));
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
            Mage::registry('current_category')->setStoreId(Mage::app()->getStore(true)->getId());
        }
        $formValues = Mage::registry('current_category')->getDefaultValues();
        if (!is_array($formValues)){
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getCategoryData()){
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getCategoryData());
            Mage::getSingleton('adminhtml/session')->setCategoryData(null);
        }
        elseif (Mage::registry('current_category')){
            $formValues = array_merge($formValues, Mage::registry('current_category')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
