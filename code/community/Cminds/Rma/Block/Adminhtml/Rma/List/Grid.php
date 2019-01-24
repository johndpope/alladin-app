<?php
class Cminds_Rma_Block_Adminhtml_Rma_List_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setDefaultSort('entity_id');
        $this->setId('rma_type_list');
        $this->setDefaultDir('asc');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $coreResource = Mage::getSingleton('core/resource');

        $collection = Mage::getModel('cminds_rma/rma')->getCollection();
        $collection->getSelect()->join( array('s' => $coreResource->getTableName('cminds_rma/rma_status')), 'main_table.status_id = s.entity_id', array('name AS status_label', 'is_closing AS is_closed'));

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('autoincrement_id', array(
            'header'    => Mage::helper('cminds_rma')->__('#'),
            'index'     => 'autoincrement_id',
        ));
        $this->addColumn('customer_name', array(
            'header'    => Mage::helper('cminds_rma')->__('Customer Name'),
            'index'     => 'customer_name',
            'renderer'  => 'Cminds_Rma_Block_Adminhtml_Rma_List_Renderer_Customer',
        ));
        $this->addColumn('status', array(
            'header'    => Mage::helper('cminds_rma')->__('Status'),
            'index'     => 'status_label',
        ));
        $this->addColumn('closed', array(
            'header'    => Mage::helper('cminds_rma')->__('Closed'),
            'index'     => 'closed',
            'renderer'  => 'Cminds_Rma_Block_Adminhtml_Rma_List_Renderer_Closed',
        ));
        $this->addColumn('action', array(
            'header' => Mage::helper('cminds_rma')->__('Action'),
            'width' => '50px',
            'type' => 'action',
            'actions' => array(
                array(
                    'caption' => Mage::helper('cminds_rma')->__('Edit'),
                    'url' => array(
                        'base' => '*/*/edit',
                    ),
                    'field' => 'id'
                ),
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'entity_id',
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return false;
    }
}