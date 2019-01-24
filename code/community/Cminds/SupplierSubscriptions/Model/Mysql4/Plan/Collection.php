<?php

class Cminds_SupplierSubscriptions_Model_Mysql4_Plan_Collection
    extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('suppliersubscriptions/plan');
    }

    public function toOptionArray() {

        $allPlans  = $this;
        $allSet = array();

        foreach($allPlans AS $plan) {
            $allSet[] = array('value' => $plan->getId(), 'label' => $plan->getName());
        }
        array_unshift($allSet, array('value' => '', 'label' => '--Please Select--'));
        return $allSet;
    }
}