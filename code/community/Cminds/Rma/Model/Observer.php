<?php

class Cminds_Rma_Model_Observer
{
    /**
     * Set Qty RMA '0' for product after Credit Memo
     *
     * @param $observer
     */
    public function updateQtyAfterCreditMemo($observer)
    {
        $orderId = $observer->getOrderId();

        $rmaEntityId = Mage::getModel('cminds_rma/rma')
            ->getCollection()
            ->addFieldToFilter('order_id', $orderId)
            ->getData()[0]['entity_id'];

        $rmaItems = Mage::getModel('cminds_rma/rma_item')
            ->getCollection()
            ->addFieldToFilter('rma_id', $rmaEntityId);

        // set '0' for Qty after Credit Memo
        foreach ($rmaItems as $item) {
            $item->setQty(0);
            $item->save();
        }
    }

}
