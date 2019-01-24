<?php

class Mss_Mdashboard_Block_Adminhtml_Mdashboard_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('mdashboard_form',
            array('legend' => 'Dashboard Information'));

        $fieldset->addField('title', 'text',
            array(
                'label'    => Mage::helper('mdashboard')->__('Title'),
                'class'    => 'required-entry',
                'required' => true,
                'name'     => 'title',
            ));

        $fieldset->addField('category_id', 'select',
            array(
                'name'     => 'category_id',
                'label'    => Mage::helper('mdashboard')->__('Categories'),
                'title'    => Mage::helper('mdashboard')->__('Categories'),
                'required' => true,
                'values'   => Mage::helper('mdashboard')->getAllCategoriesArray(),
            ));

        $fieldset->addField('order_banner', 'text',
            array(
                'name'  => 'order_banner',
                'label' => Mage::helper('mdashboard')->__('Position'),
                'title' => Mage::helper('mdashboard')->__('Position'),
                'class' => 'validate-digits',
            ));

        $fieldset->addType('thumbnail', 'Mss_Mdashboard_Block_Adminhtml_Mdashboard_Helper_Image');

        $fieldset->addField('image', 'thumbnail',
            array(
                'label' => Mage::helper('mdashboard')->__('Image'),
                'name'  => 'image',
            ));

        $fieldset->addField('status', 'select',
            array(
                'name'   => 'status',
                'label'  => Mage::helper('mdashboard')->__('Status'),
                'title'  => Mage::helper('mdashboard')->__('Status'),
                'values' => array(

                    array(
                        'value' => 0,
                        'label' => Mage::helper('core')->__('Disable'),
                    ),
                    array(
                        'value' => 1,
                        'label' => Mage::helper('core')->__('Enable'),
                    ),
                ),
            ));
            
        $fieldset->addField('size', 'select',
            array(
                'name'   => 'size',
                'label'  => Mage::helper('mdashboard')->__('Size'),
                'title'  => Mage::helper('mdashboard')->__('Size'),
                'values' => array(

                    array(
                        'value' => S,
                        'label' => Mage::helper('core')->__('S'),
                    ),
                    array(
                        'value' => M,
                        'label' => Mage::helper('core')->__('M'),
                    ),
                    array(
                        'value' => L,
                        'label' => Mage::helper('core')->__('L'),
                    ),
                    array(
                        'value' => XL,
                        'label' => Mage::helper('core')->__('XL'),
                    ),
                ),
            ));

        if (Mage::registry('mdashboard_data')) {
            $form->setValues(Mage::registry('mdashboard_data')->getData());
        }
        return parent::_prepareForm();
    }
}
