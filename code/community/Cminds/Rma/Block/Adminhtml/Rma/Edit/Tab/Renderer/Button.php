<?php

class Cminds_Rma_Block_Adminhtml_Rma_Edit_Tab_Renderer_Button
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Input
{
    public function render(Varien_Object $row)
    {
        $html = '<input type="text" ';
        $html .= 'name="' . $this->getColumn()->getId() . '" ';
        $html .= 'value="' . $row->getData($this->getColumn()->getIndex()) . '"';
        $html .= 'style="width: 75%"';
        $html .= '/>';
        $html .= '<button style="float:right"; onclick="editRmaQty(this, \''
            . $this->getUrl('*/*/editQty') . '\', '
            . $row->getEntityId()
            . '); return false">'
            . Mage::helper('cminds_rma')->__('Update')
            . '</button>';

        return $html;
    }

}
