<?php

/**
 * Cminds Product Inventory Updater Observer
 * Added here any method that can be used globally in all import types
 *
 * @category    Cminds
 * @package     Cminds_ProductInventoryUpdater
 * @author      Wojtek Kaminski <wojtek.kaminski@gmail.com>
 */
class Cminds_ProductInventoryUpdater_Model_Observer extends Varien_Object
{
	/**
     * Observer adding custom filters for vendor collection
     * For not we are fetching only wineantology vendor
     *
     * @param $event object of event data
     */
    public function collectionLoadBefore($event) {
        $event->getCollection()->addAttributeToFilter('updater_csv_link', array('neq' => null));
    }

    public function navLoad($observer) {
        $event = $observer->getEvent();
        $items = $event->getItems();

        if(Mage::helper('productinventoryupdater')->isEnabled()) {
            $items['PRODUCT_UPDATER'] =  [
                'label'     => 'Inventory Updater',
                'url'   	=> 'marketplace/productupdater/settingsView',
                'parent'    => 'IMPORT',
                'sort'     => 2
            ];
        }

        $observer->getEvent()->setItems($items);
    }
}