<?php

class Cminds_Rma_Block_Adminhtml_Rma_List_Renderer_Customer
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $customer = Mage::getModel('customer/customer')->load($row->getCustomerId());
        return $customer->getFirstname() . ' ' . $customer->getLastname();
    }
}