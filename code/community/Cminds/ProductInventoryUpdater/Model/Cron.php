<?php

/**
 * Cminds Product Inventory Updater Cron
 *
 * @category    Cminds
 * @package     Cminds_ProductInventoryUpdater
 * @author      Wojtek Kaminski <wojtek.kaminski@gmail.com>
 */
class Cminds_ProductInventoryUpdater_Model_Cron extends Varien_Object
{

    const XML_PATH_ENABLED =
        'supplierfrontendproductuploader_products/inventory_import/enable';
    const XML_PATH_LOGGING_ENABLED =
        'supplierfrontendproductuploader_products/inventory_import/logging';

    private $vendors = false;

    /**
     * Main cron method, fires all needed events
     * It raises custom event which can be used as validation for specific
     * vendor
     *
     * @return bool|void
     * @throws Exception
     */
    public function run() {

        if(!Mage::getStoreConfig(self::XML_PATH_ENABLED)) return;

        $updater = Mage::getConfig()->getNode('global/inventory_updater');

        foreach((array)$updater AS $updaterItem) {
            $updater = Mage::getModel((string)$updaterItem->class);

            if(!$updater) {
                throw new Exception("Updater does not exists");
            }

            $updater->setLoggingEnabled(
                Mage::getStoreConfig(self::XML_PATH_LOGGING_ENABLED)
            );

            $vendors = $this->_getVendors();

            foreach ($vendors as $vendor) {
                $result = Mage::dispatchEvent(
                    'cminds_product_updater_vendor_process_before',
                    array(
                        'vendor' => $vendor
                    )
                );

                if (!$result) continue;

                $updater->setVendor($vendor);

                $updater->prepare();
                $updater->run();
                $updater->notify();
            }
        }

        return true;
    }

    /**
     * Method fetches all needed vendors
     * It raises custom event which can be used to filter out unneeded vendors
     *
     * @return object
     */
    protected function _getVendors() {
        if(!$this->vendors) {
            $collection = Mage::getModel('customer/customer')->getCollection();
            $collection->addAttributeToSelect('updater_csv_column')
                       ->addAttributeToSelect('updater_qty_column')
                       ->addAttributeToSelect('updater_csv_action')
                       ->addAttributeToSelect('updater_cost_column')
                       ->addAttributeToSelect('updater_csv_attribute')
                       ->addAttributeToSelect('updater_csv_delimiter');

            Mage::dispatchEvent('cminds_product_updater_collection_load_before',
                array(
                    'collection' => $collection
                ));

            $this->vendors = $collection;
        }
        return $this->vendors;
    }
}
