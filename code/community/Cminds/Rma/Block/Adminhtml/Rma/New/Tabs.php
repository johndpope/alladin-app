<?php

class Cminds_Rma_Block_Adminhtml_Rma_New_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('new_tabs');
        $this->setDestElementId('new_form');
    }

    protected function _beforeToHtml()
    {
        $this->addTab('newrma', array(
            'label' => $this->__('New RMA'),
            'title' => $this->__('New RMA'),
            'content' => $this->getLayout()->createBlock('cminds_rma/adminhtml_rma_new_tab_newrma')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }

}