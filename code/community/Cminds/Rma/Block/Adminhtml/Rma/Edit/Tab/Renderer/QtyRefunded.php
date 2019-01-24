<?php

class Cminds_Rma_Block_Adminhtml_Rma_Edit_Tab_Renderer_QtyRefunded extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $_product = Mage::getModel('sales/order_item')
            ->load($row->getItemId());
        $qtyRefunded = $_product->getQtyRefunded();

        return (int)$qtyRefunded;
    }

}
