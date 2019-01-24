<?php

class Mss_Mdashboard_Block_Adminhtml_Mdashboard_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('MdashboardGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);

    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('mdashboard/mdashboard')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id',
            array(
                'header' => 'ID',
                'align'  => 'right',
                'width'  => '50px',
                'index'  => 'id',
            ));

        $this->addColumn('title',
            array(
                'header' => 'Title',
                'align'  => 'left',
                'index'  => 'title',
            ));

        $this->addColumn('category_id',
            array(
                'header'   => 'Category',
                'align'    => 'left',
                'index'    => 'category_id',
                'renderer' => 'Mss_Mdashboard_Block_Adminhtml_Mdashboard_Rendrer_Category',
            ));

        $this->addColumn('order_banner',
            array(
                'header' => 'Position',
                'align'  => 'left',
                'index'  => 'order_banner',
            ));

        $this->addColumn('status',
            array(
                'header'  => $this->__('Status'),
                'width'   => '120',
                'align'   => 'left',
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(0 => $this->__('Disabled'), 1 => $this->__('Enabled')),
            ));

        return parent::_prepareColumns();
    }
    
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}
