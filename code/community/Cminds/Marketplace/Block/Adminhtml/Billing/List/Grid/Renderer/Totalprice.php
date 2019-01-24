<?php

class Cminds_Marketplace_Block_Adminhtml_Billing_List_Grid_Renderer_Totalprice
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {

        $supplierId = $row->getData('supplier_id');
        $orderId = $row->getData('order_id');

        $totalPrice = Mage::helper('marketplace/order')
            ->calcSuppliersTotalPrice($supplierId, $orderId);

        return Mage::helper('core')->currency($totalPrice, true, false);
    }
}