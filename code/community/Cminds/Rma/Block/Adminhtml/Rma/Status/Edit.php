<?php

class Cminds_Rma_Block_Adminhtml_Rma_Status_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'cminds_rma';
        $this->_controller = 'adminhtml_rma_status';
        $this->_mode = 'edit';
    }

    public function getHeaderText()
    {
        if (Mage::registry('current_status') && Mage::registry('current_status')->getId())
        {
            return Mage::helper('cminds_rma')->__('Edit Status');
        } else {
            return Mage::helper('cminds_rma')->__('New Status');
        }
    }

}