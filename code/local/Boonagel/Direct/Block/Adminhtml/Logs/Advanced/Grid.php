<?php

class Boonagel_Direct_Block_Adminhtml_Logs_Advanced_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('direct_advanced_logs');
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInsession(false);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('direct/directlog')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareGrid() {

        $this->addColumn('id', array(
            'header' => 'ID',
            'index' => 'id'
        ));

        $this->addColumn('order_id', array(
            'header' => 'ORDER ID',
            'index' => 'order_id'
        ));

        $this->addColumn('created_at', array(
            'header' => 'DATE',
            'index' => 'created_at'
        ));

        $this->addColumn('transaction_token', array(
            'header' => 'TRANSACTION TOKEN',
            'index' => 'transaction_token'
        ));
        
        $this->addColumn('status', array(
            'header' => 'STATUS',
            'type' => 'options',
            'options' => array('complete','unverified'),
            'index' => 'status'
        ));

        parent::_prepareGrid();
    }

}
