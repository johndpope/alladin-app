<?php

class Cminds_SupplierSubscriptions_Model_Config_Plan_Priority {
//    public function toOptionArray() {
//        $uploader = array(
//            array('label'=>'jpg', 'value'=>'jpg'),
//            array('label'=>'jpeg', 'value'=>'jpeg'),
//            array('label'=>'pdf', 'value'=>'pdf'),
//            array('label'=>'png', 'value'=>'png'),
//            array('label'=>'gif', 'value'=>'gif'),
//            array('label'=>'csv', 'value'=>'csv'),
//            array('label'=>'zip', 'value'=>'zip'),
//        );
//        return $uploader;
//
//    }
    public function toArray() {
        $uploader = array(
            1 => 'Low',
            2 => 'Medium',
            3 => 'High',
            4 => 'Very High',
            5 => 'Superme',
        );
        return $uploader;
    }
}