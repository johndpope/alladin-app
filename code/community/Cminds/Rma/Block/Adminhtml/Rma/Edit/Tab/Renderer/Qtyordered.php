<?php

class Cminds_Rma_Block_Adminhtml_Rma_Edit_Tab_Renderer_QtyOrdered extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $_product = Mage::getModel('sales/order_item')
            ->load($row->getItemId());
        $qtyOrdered = $_product->getQtyOrdered();

        return (int)$qtyOrdered;
    }

}
