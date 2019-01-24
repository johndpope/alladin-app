<?php
class Cminds_SupplierSubscriptions_Block_Adminhtml_Plan_List extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'suppliersubscriptions';
        $this->_controller = 'adminhtml_plan_list';
        $this->_headerText = $this->__('Subscription Plan list');

        parent::__construct();
    }
}