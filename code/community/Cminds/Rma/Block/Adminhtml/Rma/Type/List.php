<?php
class Cminds_Rma_Block_Adminhtml_Rma_Type_List extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'cminds_rma';
        $this->_controller = 'adminhtml_rma_type_list';
        $this->_headerText = $this->__('RMA - Types List');

        parent::__construct();
    }
}