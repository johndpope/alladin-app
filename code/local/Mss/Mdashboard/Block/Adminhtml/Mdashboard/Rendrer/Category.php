<?php

class Mss_Mdashboard_Block_Adminhtml_Mdashboard_Rendrer_Category extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $value = $row->getData('category_id');
        $catName = Mage::helper('mdashboard')->getCatName($value);
                
        return $catName;
    }
}
