<?php

class Cminds_Marketplace_Block_Adminhtml_Billing_List_Grid_Renderer_Netincomediscount
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $supplierId = $row->getData('supplier_id');
        $orderId = $row->getData('order_id');

        $incomeDiscount = Mage::helper('marketplace/order')
            ->calcSuppliersNetIncomeDiscount($supplierId, $orderId);

        return Mage::helper('core')->currency($incomeDiscount, true, false);
    }

}