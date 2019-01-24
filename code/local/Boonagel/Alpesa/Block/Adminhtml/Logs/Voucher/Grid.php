<?php

class Boonagel_Alpesa_Block_Adminhtml_Logs_Voucher_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('alpesa_voucher_payment_logs');
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInsession(false);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('alpesa/alpesavoucher')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareGrid() {

        $this->addColumn('id', array(
            'header' => 'ID',
            'index' => 'id'
        ));

        $this->addColumn('validated', array(
            'header' => 'VALIDATED',
            'type' => 'options',
            'options' => array('0', '1'),
            'index' => 'validated'
        ));


        $this->addColumn('order_id', array(
            'header' => 'ORDER ID',
            'index' => 'order_id'
        ));


        $this->addColumn('order_amount', array(
            'header' => 'ORDER AMOUNT',
            'index' => 'order_amount'
        ));

        $this->addColumn('voucher_amount', array(
            'header' => 'VOUCHER AMOUNT',
            'index' => 'voucher_amount'
        ));

        //$link = Mage::helper('adminhtml')->getUrl('adminhtml/alpesa/wallet/id/'.$id);
//        $this->addColumn('action_edit', array(
//            'header' => 'VALIDATE',
//            'sortable' => false,
//            'filter' => false,
//            'type' => 'action',
//            'getter'    => 'getId',
//            'actions' => array(
//                array(
//                    'url' => array('base'=> '*/*/wallet'),
//                    'caption' => 'Validate',
//                    'field' => 'id',
//                ),
//            )
//        ));

        parent::_prepareGrid();
    }

        public function getRowUrl($row){
        if($row->getValidated() == 0){
            return $this->getUrl('*/*/voucherpay', array('id' => $row->getId()));
        }else{
            return null;
        }

}
}
