<?php

class Cminds_SupplierRedirection_Block_Domain
    extends Mage_Core_Block_Template {

    protected function _construct() {
        parent::_construct();
    }

    public function getFormActionUrl()
    {
        return $this->getUrl('supplier/domain/settingsPost');
    }

    public function getDomain() {
        return $this->getVendor()->getDomainUrl();
    }

    public function getVendor() {
        /** @var Cminds_Marketplace_Helper_Data $dataHelper */
        $dataHelper = Mage::helper("marketplace");
        return $dataHelper->getLoggedSupplier();
    }
}
