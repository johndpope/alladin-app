<?php

class Cminds_Rma_Block_Adminhtml_Rma_Type_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method' => 'post',
            'enctype' => 'multipart/form-data',
        ));

        $form->setUseContainer(true);

        $this->setForm($form);

        $fieldset = $form->addFieldset('type_form', array(
            'legend' =>Mage::helper('cminds_rma')->__('Type Form')
        ));

        $fieldset->addField('entity_id', 'hidden', array(
            'name'      => 'entity_id',
        ));

        $fieldset->addField('name', 'text', array(
            'label'     => Mage::helper('cminds_rma')->__('Name'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'name',
        ));

        $fieldset->addField('sort_order', 'text', array(
            'label'     => Mage::helper('cminds_rma')->__('Sort'),
            'name'      => 'sort_order',
        ));

        $data = Mage::registry('current_type')->getData();

        $form->setValues($data);

        return parent::_prepareForm();
    }
}