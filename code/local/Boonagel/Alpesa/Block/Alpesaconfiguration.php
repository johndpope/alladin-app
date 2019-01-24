<?php

class Boonagel_Alpesa_Block_Alpesaconfiguration extends Mage_Core_Block_Template {

    public function testfunction() {

        return "";
    }

    public function configData() {
        $data['configuration'] = $this->_getModelCollection('alpesa/alpesaconfig');
        $data['conditions'] = $this->_getModelCollection('alpesa/alpesacondition');
        $data['cards'] = $this->_getModelCollection('alpesa/alpesacard');
        return $data;
    }

    private function _getModelCollection($modelType) {

        //delete all the card records
        $alpesaModel = Mage::getModel($modelType);
        $alpesaModelCreated = $alpesaModel->getCollection();
        return $alpesaModelCreated;
    }
    
    public function offlineAccess(){
        $id  = Mage::registry('entityId');
        $array = array();
        //$customer = Mage::getModel('customer/customer')->getCollection();
        $customer = Mage::helper('Boonagel_Alpesa')->dynoData('customer/customer', array('entity_id,eq,' . $id));
        if($customer->count() < 1){
            return null;
        }
        
        $array[1] = false;
        $walletData = Mage::helper('Boonagel_Alpesa')->dynoData('alpesa/alpesawallet', array('user_id,eq,' . $id), 1);
        if($walletData->count() == 1){
            $array[1] = true;
            $array[2] = $walletData;
        }
        $array[0] = $customer;
        return $array;
    }


}
