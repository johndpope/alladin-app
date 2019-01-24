<?php

class Cminds_OrderSplit_Model_CatalogInventory_Observer extends Mage_CatalogInventory_Model_Observer
{

    protected function _getQuoteItemQtyForCheck($productId, $quoteItemId, $itemQty)
    {
        if (Mage::registry("refresh-cache")) {
            Mage::unregister("refresh-cache");
            $this->_checkedQuoteItems = array();
        }

        return parent::_getQuoteItemQtyForCheck($productId, $quoteItemId, $itemQty);
    }
}
