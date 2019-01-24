<?php

class Cminds_Rma_Block_Adminhtml_Rma_New_Tab_Newrma extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset(
            'new_rma',
            array('legend' => Mage::helper('catalog')->__('Select Order for RMA'))
        );

        $fieldset->addField('order_id', 'select', array(
            'label' => Mage::helper('catalog')->__('Order #'),
            'title' => Mage::helper('catalog')->__('Order #'),
            'name' => 'order_id',
            'value' => '',
            'values' => Mage::getModel('cminds_rma/rma_adminedit')
                ->getOrderCollection()
        ));

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

}
