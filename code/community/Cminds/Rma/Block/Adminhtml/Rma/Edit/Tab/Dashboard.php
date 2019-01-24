<?php

class Cminds_Rma_Block_Adminhtml_Rma_Edit_Tab_Dashboard extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $fieldset = $form->addFieldset('general_info', array(
            'legend' => $this->__('General Info')
        ));

        $fieldset->addField('id', 'hidden', array());

        $fieldset->addField('rma_autoinrecement_id', 'note', array(
            'label'     => $this->__('ID'),
            'text'      => '#'.Mage::registry('rma_data')->getData('autoincrement_id'),
        ));

        $fieldset->addField('order_id', 'note', array(
            'text' => "#<a href='".Mage::helper('adminhtml')->getUrl("adminhtml/sales_order/view",
                    array('order_id' => Mage::registry('rma_data')->getOrderId()))."'>"
                . Mage::registry('rma_data')->getOrder()->getIncrementId() . "</a>",
            'label' => $this->__('Order ID'),
        ));

        $fieldset->addField('created_on', 'note', array(
            'text' => Mage::registry('rma_data')->getData('created_at'),
            'label' => $this->__('Created On'),
        ));
        $fieldset = $form->addFieldset('options', array(
            'legend' => $this->__('Options')
        ));

        $statusCollection = Mage::getModel('cminds_rma/rma_status')->getCollection();

        $fieldset->addField('status', 'select', array(
            'name' => 'status_id',
            'label' => Mage::helper('cminds_rma')->__('Status'),
            'values' => $statusCollection->toOptionArray(),
            'value' => Mage::registry('rma_data')->getStatusId(),
            'required' => false,
        ));
        $fieldset->addField('is_package_opened', 'select', array(
            'name' => 'is_package_opened',
            'label' => Mage::helper('cminds_rma')->__('Package Opened'),
            'values' => array(
                array(
                    'value' => 0,
                    'label' => Mage::helper('core')->__('No'),
                ),
                array(
                    'value' => 1,
                    'label' => Mage::helper('core')->__('Yes'),
                )
            ),
            'value' => Mage::registry('rma_data')->getIsPackageOpened(),
            'required' => false,
        ));

        $reasonCollection = Mage::getModel('cminds_rma/rma_reason')->getCollection();

        $fieldset->addField('reason', 'select', array(
            'name' => 'reason_id',
            'label' => Mage::helper('cminds_rma')->__('Reason'),
            'values' => $reasonCollection->toOptionArray(),
            'value' => Mage::registry('rma_data')->getReasonId(),
            'required' => false,
        ));
        $fieldset->addField('additional_information', 'textarea', array(
            'name' => 'additional_information',
            'label' => Mage::helper('cminds_rma')->__('Additional Information'),
            'required' => false,
        ));

        $fieldset = $form->addFieldset('add_comment', array(
            'legend' => $this->__('Quick - Add New Comment')
        ));

        $fieldset->addField('comment', 'textarea', array(
            'name' => 'comment',
            'label' => Mage::helper('cminds_rma')->__('Add Comment'),
            'required' => false,
            'after_element_html' => '<i>This is quick note, customer will not be notified</i>'
        ));

        $fieldset = $form->addFieldset('last_comment', array(
        ));

        $lastComment = Mage::registry('rma_data')->getAllStatusHistory()->getLastItem();

        $fieldset->addField('last_comment_text', 'note', array(
            'text' => $lastComment->getCommentBody(),
        ));

        $form->setValues(Mage::registry('rma_data')->getData());

        $this->setForm($form);

        return parent::_prepareForm();
    }

}