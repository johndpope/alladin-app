<?php
class Cminds_Rma_Block_Adminhtml_Rma_List extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'cminds_rma';
        $this->_controller = 'adminhtml_rma_list';
        $this->_headerText = $this->__('Requests List');

        parent::__construct();
    }
}