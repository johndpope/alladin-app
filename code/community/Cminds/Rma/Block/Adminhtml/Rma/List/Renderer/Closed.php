<?php

class Cminds_Rma_Block_Adminhtml_Rma_List_Renderer_Closed
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        return $row->getIsClosed() == 1 ? $this->__('Yes') : $this->__('No');
    }
}

?>