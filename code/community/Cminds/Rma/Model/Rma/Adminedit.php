<?php

class Cminds_Rma_Model_Rma_Adminedit extends Mage_Core_Model_Abstract
{
    /**
     * Create Orders list for dropdown.
     *
     * @return array
     */
    public function getOrderCollection()
    {
        $orderCollection = Mage::getModel('sales/order')
            ->getCollection();

        $orderArray = array();
        foreach ($orderCollection as $order) {
            $id = $order->getId();
            $incrementId = $order->getIncrementId();
            $orderArray[$id] = $incrementId;
        }

        return $orderArray;
    }

}