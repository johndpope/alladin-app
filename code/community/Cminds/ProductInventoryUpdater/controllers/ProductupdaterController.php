<?php

class Cminds_ProductInventoryUpdater_ProductupdaterController
    extends Cminds_Marketplace_Controller_Action
{

    public function settingsViewAction()
    {
        $this->_renderBlocks();
    }

    public function saveSettingsAction()
    {
        $helper = Mage::helper('productinventoryupdater');
        $postData = $this->getRequest()->getPost();
        $supplier = Mage::getModel('customer/customer')->load(Mage::getSingleton('customer/session')->getId());

        try {

            $supplier->setUpdaterCsvLink($postData['updater_csv_link']);
            $supplier->setUpdaterCsvColumn($postData['updater_csv_column']);
            $supplier->setUpdaterQtyColumn($postData['updater_qty_column']);
            $supplier->setUpdaterCostColumn($postData['updater_cost_column']);
            $supplier->setUpdaterCsvAction($postData['updater_csv_action']);
            $supplier->setUpdaterCsvAttribute($postData['matching_attribute']);
            $supplier->setUpdaterCsvDelimiter($postData['updater_csv_delimiter']);
            $supplier->save();
            Mage::getSingleton('core/session')->addSuccess($helper->__(
                'Updater Settings was saved'
            ));
            Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl('marketplace/productupdater/settingsView'));
        } catch(Exception $e) {
            Mage::getSingleton('core/session')->addError($e->getMessage());
            Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl('marketplace/productupdater/settingsView'));
        }
    }

}