<?php

class Cminds_SupplierSubscriptions_Block_Adminhtml_Plan_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'suppliersubscriptions';
        $this->_controller = 'adminhtml_plan';
        $this->_mode = 'edit';
    }

    public function getHeaderText()
    {
        if (Mage::registry('plan_data') && Mage::registry('plan_data')->getId())
        {
            return Mage::helper('suppliersubscriptions')->__('Edit Subscription Plan %s', $this->escapeHtml(Mage::registry('plan_data')->getName()));
        } else {
            return Mage::helper('suppliersubscriptions')->__('New Subscription Plan');
        }
    }

}