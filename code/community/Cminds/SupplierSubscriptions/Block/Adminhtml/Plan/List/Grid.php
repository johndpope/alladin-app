<?php
class Cminds_SupplierSubscriptions_Block_Adminhtml_Plan_List_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setDefaultSort('id');
        $this->setId('billing_list_grid');
        $this->setDefaultDir('asc');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('suppliersubscriptions/plan')->getCollection();

        $this->setCollection($collection);
        $s =  parent::_prepareCollection();

        return $s;
    }

    protected function _prepareColumns()
    {
        $yesnoOptions = array('0' => 'No','1' => 'Yes','' => 'No');
        $this->addColumn('id', array(
            'header'    => Mage::helper('suppliersubscriptions')->__('ID'),
            'width'     => '50px',
            'index'     => 'id',
        ));
        $this->addColumn('name', array(
            'header'    => Mage::helper('suppliersubscriptions')->__('Name'),
            'index'     => 'name',
        ));
        $this->addColumn('products_count', array(
            'header'    => Mage::helper('suppliersubscriptions')->__('Number of Products'),
            'index'     => 'products_count',
        ));
        $this->addColumn('images_per_product', array(
            'header'    => Mage::helper('suppliersubscriptions')->__('Images Per Product'),
            'index'     => 'images_per_product',
        ));
        $this->addColumn('price', array(
            'header'    => Mage::helper('suppliersubscriptions')->__('Price'),
            'index'     => 'price',
        ));
        $this->addColumn('created_at', array(
            'header'    => Mage::helper('suppliersubscriptions')->__('Created On'),
            'index'     => 'created_at',
        ));

        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('suppliersubscriptions')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('suppliersubscriptions')->__('Delete'),
                        'url'       => array('base'=> '*/*/delete'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
            ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}