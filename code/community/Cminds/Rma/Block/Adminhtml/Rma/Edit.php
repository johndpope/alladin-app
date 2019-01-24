<?php

class Cminds_Rma_Block_Adminhtml_Rma_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_blockGroup = 'cminds_rma';
        $this->_controller = 'adminhtml_rma';
        $this->_mode = 'edit';

        if(Mage::registry('rma_data')->getOrder()->canCreditmemo()) {
            $this->_addButton('button_save_credit_memo', array(
                'label' => Mage::helper('adminhtml')->__('Credit Memo'),
                'onclick' => "setLocation('" . $this->getUrl('*/*/creditmemo', array('id' => Mage::registry('rma_data')->getId())) . "')",
                'class' => 'scalable go'
            ), 0, 100, 'header');
        }
    }

    public function getHeaderText() {
        return Mage::helper('cminds_rma')->__('Edit RMA #%s', Mage::registry('rma_data')->getAutoincrementId());
    }

}