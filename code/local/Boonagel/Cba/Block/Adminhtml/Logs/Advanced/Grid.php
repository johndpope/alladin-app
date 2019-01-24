<?php

class Boonagel_Cba_Block_Adminhtml_Logs_Advanced_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('cba_logs_errors');
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInsession(false);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('cba/cbalog')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareGrid() {

        $this->addColumn('id', array(
            'header' => 'ID',
            'index' => 'id'
        ));

        $this->addColumn('narrative', array(
            'header' => 'ORDER ID',
            'index' => 'narrative'
        ));


        $this->addColumn('trans_id', array(
            'header' => 'TRANS ID',
            'index' => 'trans_id'
        ));

        $this->addColumn('trans_amount', array(
            'header' => 'TRANS AMOUNT',
            'index' => 'trans_amount'
        ));

        $this->addColumn('phone_nr', array(
            'header' => 'PHONE',
            'index' => 'phone_nr'
        ));

        $this->addColumn('created_at', array(
            'header' => 'DATE',
            'index' => 'created_at'
        ));

        $this->addColumn('customer_name', array(
            'header' => 'NAMES',
            'index' => 'customer_name'
        ));
        
        $this->addColumn('erronous', array(
            'header' => 'ERRONOUS',
            'type' => 'options',
            'options' => array('0', '1'),
            'index' => 'erronous'
        ));

        parent::_prepareGrid();
    }

}
