<?php

class Cminds_Rma_Block_Adminhtml_Rma_Reason_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'cminds_rma';
        $this->_controller = 'adminhtml_rma_reason';
        $this->_mode = 'edit';
    }

    public function getHeaderText()
    {
        if (Mage::registry('current_reason') && Mage::registry('current_reason')->getId())
        {
            return Mage::helper('cminds_rma')->__('Edit Reason');
        } else {
            return Mage::helper('cminds_rma')->__('New Reason');
        }
    }

}