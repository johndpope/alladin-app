<?php

class Cminds_SupplierSubscriptions_Block_Adminhtml_Plan_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        if (Mage::registry('plan_data')){
            $data = Mage::registry('plan_data')->getData();
        } else {
            $data = array();
        }

        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method' => 'post',
            'enctype' => 'multipart/form-data',
        ));

        $form->setUseContainer(true);

        $this->setForm($form);

        $fieldset = $form->addFieldset('plan_form', array(
            'legend' =>Mage::helper('suppliersubscriptions')->__('Subscription Info')
        ));

        $fieldset->addField('id', 'hidden', array(
            'name'      => 'id',
        ));

        $fieldset->addField('name', 'text', array(
            'label'     => Mage::helper('suppliersubscriptions')->__('Name'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'name',
        ));

        $fieldset->addField('price', 'text', array(
            'label'     => Mage::helper('suppliersubscriptions')->__('Price'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'price',
        ));

        $fieldset->addField('products_count', 'text', array(
            'label'     => Mage::helper('suppliersubscriptions')->__('Number of Products'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'products_count',
        ));

        $fieldset->addField('images_per_product', 'text', array(
            'label'     => Mage::helper('suppliersubscriptions')->__('Images Number Per Product'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'images_per_product',
        ));

        $form->setValues($data);

        return parent::_prepareForm();
    }
}