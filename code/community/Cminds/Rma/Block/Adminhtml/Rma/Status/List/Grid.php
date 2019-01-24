<?php
class Cminds_Rma_Block_Adminhtml_Rma_Status_List_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setDefaultSort('entity_id');
        $this->setId('adminhtml_rma_status_list');
        $this->setDefaultDir('asc');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('cminds_rma/rma_status')->getCollection()->addFilter('is_system', 0);
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('cminds_rma')->__('ID'),
            'index'     => 'entity_id',
        ));
        $this->addColumn('name', array(
            'header'    => Mage::helper('cminds_rma')->__('Name'),
            'index'     => 'name',
        ));
        $this->addColumn('sort', array(
            'header'    => Mage::helper('cminds_rma')->__('Sort'),
            'index'     => 'sort_order',
        ));
        $this->addColumn('is_closing', array(
            'header'    => Mage::helper('cminds_rma')->__('Closing Request'),
            'index'     => 'is_closing',
        ));
        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return false;
    }
}