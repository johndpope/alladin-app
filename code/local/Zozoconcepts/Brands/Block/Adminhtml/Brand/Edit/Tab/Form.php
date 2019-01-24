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
 * Brand edit form tab
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Brands
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Brands_Block_Adminhtml_Brand_Edit_Tab_Form
    extends Mage_Adminhtml_Block_Widget_Form {
    /**
     * prepare the form
     * @access protected
     * @return Brands_Brand_Block_Adminhtml_Brand_Edit_Tab_Form
     * @author Zozoconcepts Hybrid
     */
    protected function _prepareForm(){
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('brand_');
        $form->setFieldNameSuffix('brand');
        $this->setForm($form);
        $fieldset = $form->addFieldset('brand_form', array('legend'=>Mage::helper('zozoconcepts_brands')->__('Brand')));
        $fieldset->addType('image', Mage::getConfig()->getBlockClassName('zozoconcepts_brands/adminhtml_brand_helper_image'));
        $fieldset->addType('file', Mage::getConfig()->getBlockClassName('zozoconcepts_brands/adminhtml_brand_helper_file'));
        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig();

        $fieldset->addField('title', 'text', array(
            'label' => Mage::helper('zozoconcepts_brands')->__('Title'),
            'name'  => 'title',
            'required'  => true,
            'class' => 'required-entry',

        ));

        $fieldset->addField('brand_icon', 'image', array(
            'label' => Mage::helper('zozoconcepts_brands')->__('Brand Icon'),
            'name'  => 'brand_icon',

        ));

        $fieldset->addField('brand_image', 'image', array(
            'label' => Mage::helper('zozoconcepts_brands')->__('Brand Image'),
            'name'  => 'brand_image',

        ));

        $fieldset->addField('brand_descriptions', 'editor', array(
            'label' => Mage::helper('zozoconcepts_brands')->__('Brand Descriptions'),
            'name'  => 'brand_descriptions',
            'config' => $wysiwygConfig,

        ));

       /* $fieldset->addField('verified_ownerships', 'file', array(
            'label' => Mage::helper('zozoconcepts_brands')->__('Verified Owner documents'),
            'name'  => 'verified_ownerships',
            'note'	=> $this->__('Upload Brand Owner Documents If any, this will be downloaded by user for brand verification'),

        ));*/

        $fieldset->addField('featured_brands', 'select', array(
            'label' => Mage::helper('zozoconcepts_brands')->__('Featured Brands'),
            'name'  => 'featured_brands',
            'note'	=> $this->__('Only featured brands will be shown on Brand slider'),
            'required'  => true,
            'class' => 'required-entry',

            'values'=> array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('zozoconcepts_brands')->__('Yes'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('zozoconcepts_brands')->__('No'),
                ),
            ),
        ));
        $fieldset->addField('url_key', 'text', array(
            'label' => Mage::helper('zozoconcepts_brands')->__('Url key'),
            'name'  => 'url_key',
            'note'    => Mage::helper('zozoconcepts_brands')->__('Relative to Website Base URL')
        ));
        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('zozoconcepts_brands')->__('Status'),
            'name'  => 'status',
            'values'=> array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('zozoconcepts_brands')->__('Enabled'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('zozoconcepts_brands')->__('Disabled'),
                ),
            ),
        ));
        if (Mage::app()->isSingleStoreMode()){
            $fieldset->addField('store_id', 'hidden', array(
                'name'      => 'stores[]',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
            Mage::registry('current_brand')->setStoreId(Mage::app()->getStore(true)->getId());
        }
        $formValues = Mage::registry('current_brand')->getDefaultValues();
        if (!is_array($formValues)){
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getBrandData()){
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getBrandData());
            Mage::getSingleton('adminhtml/session')->setBrandData(null);
        }
        elseif (Mage::registry('current_brand')){
            $formValues = array_merge($formValues, Mage::registry('current_brand')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
