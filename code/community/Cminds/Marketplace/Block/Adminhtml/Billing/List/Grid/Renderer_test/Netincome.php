<?php

class Cminds_Marketplace_Block_Adminhtml_Billing_List_Grid_Renderer_Netincome
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $supplierId = $row->getData('supplier_id');
        $orderId = $row->getData('order_id');

        $income = Mage::helper('marketplace/order')
            ->calcSuppliersNetIncome($supplierId, $orderId);

        return Mage::helper('core')->currency($income, true, false);
    }

}