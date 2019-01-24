<?php
    class Cminds_SupplierRedirection_Model_Product
    extends Mage_Catalog_Model_Product {

        public function getProductUrl($useSid = NULL) {
            $parentUrl = parent::getProductUrl($useSid);

            if(!$this->getCreatorId()) {
                return $parentUrl;
            }

            $vendor = Mage::getModel('customer/customer')
                ->load($this->getCreatorId());

            if(!$vendor->getId() || !$vendor->getDomainUrl()) {
                return $parentUrl;
            }

            $baseUrl = Mage::getBaseUrl(
                Mage_Core_Model_Store::URL_TYPE_WEB,
                Mage::app()->getStore()->isCurrentlySecure()
            );

            $url = str_replace(
                $baseUrl,
                'http://' . $vendor->getDomainUrl() . '/',
                $parentUrl
            );

            return $url;
        }
    }