<?php

class Cminds_Rma_Block_Adminhtml_Rma_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct()
    {
        parent::__construct();
        $this->setId('edit_tabs');
        $this->setDestElementId('edit_form');
//        $this->setTitle($this->__('RMA #%s', Mage::registry('rma_data')->getAutoincrementId()));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('general', array(
            'label' => $this->__('General'),
            'title' => $this->__('General'),
            'content' => $this->getLayout()->createBlock('cminds_rma/adminhtml_rma_edit_tab_dashboard')->toHtml(),
        ));
        $this->addTab('products', array(
            'label' => $this->__('Products'),
            'title' => $this->__('Products'),
            'content' => $this->getLayout()->createBlock('cminds_rma/adminhtml_rma_edit_tab_products')->toHtml(),
        ));
        $this->addTab('creditmemo', array(
            'label' => $this->__('Credit Memo'),
            'title' => $this->__('Credit Memo'),
            'content' => $this->getLayout()->createBlock('cminds_rma/adminhtml_rma_edit_tab_memo')->toHtml(),
        ));
        $this->addTab('address', array(
            'label' => $this->__('Customer Address'),
            'title' => $this->__('Customer Address'),
            'content' => $this->getLayout()->createBlock('cminds_rma/adminhtml_rma_edit_tab_address')->toHtml(),
        ));
        $this->addTab('note', array(
            'label' => $this->__('Notes'),
            'title' => $this->__('Notes'),
            'content' => $this->getLayout()->createBlock('cminds_rma/adminhtml_rma_edit_tab_note')->toHtml(),
        ));
        return parent::_beforeToHtml();
    }
}