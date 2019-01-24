<?php
class Cminds_SupplierTrading_Block_Adminhtml_Trades extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {

        $this->_blockGroup = 'suppliertrading';
        $this->_controller = 'adminhtml_trades';
        $this->_headerText = $this->__('Trades');

        parent::__construct();
        $this->_removeButton('add');
    }
}