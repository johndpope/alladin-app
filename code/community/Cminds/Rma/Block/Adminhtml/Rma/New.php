<?php

class Cminds_Rma_Block_Adminhtml_Rma_New extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_blockGroup = 'cminds_rma';
        $this->_controller = 'adminhtml_rma';
        $this->_mode = 'new';

        $this->_addButton('saveandcontinue', array(
            'label' => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick' => 'setSetingOrder(\'' . $this->getUrl('*/*/continue') . '\')',
            'class' => 'save',
        ), -100);
    }

    /**
     * Get Header text.
     *
     * @return string
     */
    public function getHeaderText()
    {
        return Mage::helper('cminds_rma')->__('New RMA #');
    }

}
