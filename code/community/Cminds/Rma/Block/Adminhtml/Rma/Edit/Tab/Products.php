<?php

class Cminds_Rma_Block_Adminhtml_Rma_Edit_Tab_Products extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setDefaultSort('entity_id');
        $this->setId('rma_type_list');
        $this->setDefaultDir('asc');
        $this->setSaveParametersInSession(true);
         $this->setFilterVisibility(false);
         $this->setPagerVisibility(false);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::registry('rma_data')->getAllItems();

        Mage::dispatchEvent('adminhtml_cminds_rma_prepare_collection_after', array( 'collection' => $collection ));

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('cminds_rma')->__('#'),
            'index'     => 'entity_id',
        ));
        $this->addColumn('item_id', array(
            'header'    => Mage::helper('cminds_rma')->__('Order Item ID'),
            'index'     => 'item_id',
        ));
        $this->addColumn('product_name', array(
            'header'    => Mage::helper('cminds_rma')->__('Product Name'),
            'index'     => 'product_name',
            'renderer'  => 'Cminds_Rma_Block_Adminhtml_Rma_Edit_Tab_Renderer_Product'
        ));
        $this->addColumn('qty_ordered', array(
            'header'    => Mage::helper('cminds_rma')->__('Oty Ordered'),
            'index'     => 'qty_ordered',
            'renderer'  => 'Cminds_Rma_Block_Adminhtml_Rma_Edit_Tab_Renderer_QtyOrdered'
        ));
        $this->addColumn('qty_refunded', array(
            'header' => Mage::helper('cminds_rma')->__('Oty Refunded'),
            'index' => 'qty_refunded',
            'renderer' => 'Cminds_Rma_Block_Adminhtml_Rma_Edit_Tab_Renderer_QtyRefunded'
        ));
        $this->addColumn('qty', array(
            'header'    => Mage::helper('cminds_rma')->__('Qty'),
            'index'     => 'qty',
        ));
        $this->addColumn('update_qty', array(
            'header' => Mage::helper('cminds_rma')->__('Qty for RMA'),
            'index' => 'update_qty',
            'renderer' => 'Cminds_Rma_Block_Adminhtml_Rma_Edit_Tab_Renderer_Button'
        ));

        Mage::dispatchEvent('adminhtml_cminds_rma_prepare_columns_after', array( 'grid' => $this ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return false;
    }

}