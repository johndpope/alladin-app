<?php
class Cminds_SupplierTrading_Block_Adminhtml_Trades_Grid_Renderer_Status extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        if ($row->getStatus() == 0) {
            return $this->__('Waiting for action');
        } elseif ($row->getStatus() == 1) {
            return $this->__('Rejected');
        } elseif ($row->getStatus() == 2) {
            return $this->__('Accepted');
        }
    }
}
