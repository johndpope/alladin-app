<?php
class Cminds_Rma_Block_Adminhtml_Rma_Edit_Tab_Renderer_Product extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $_product = Mage::getModel("catalog/product")->load($row->getProductId());
            $ret = "<a href='".Mage::helper("adminhtml")->getUrl("adminhtml/catalog_product/edit/",
                    array("id"=>$row->getProductId()))."'>" . $_product->getName() . "</a>";

        return $ret;
    }
}