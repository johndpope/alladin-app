<?php
class Cminds_SupplierTrading_Block_Adminhtml_Trades_Grid_Renderer_Product extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

    public function render(Varien_Object $row)
    {
        return $row->getName().' (<a href="'.Mage::helper("adminhtml")->getUrl("adminhtml/catalog_product/edit/id/".$row->getProductId()).'">'.$row->getSku().'</a>)';
    }

}
?>