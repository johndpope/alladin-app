<?php

class Cminds_ProductInventoryUpdater_Block_Settings extends Mage_Core_Block_Template
{

    public function getSupplier()
    {
        $supplier = Mage::getModel('customer/customer')->load(
            Mage::helper('supplierfrontendproductuploader')->getSupplierId()
        );

        return $supplier;
    }

    public function getActionOptions()
    {
        $attribute = Mage::getSingleton('eav/config')
            ->getAttribute('customer', 'updater_csv_action');

        $allOptions = $attribute->getSource()->getAllOptions(false);

        return $allOptions;
    }

    public function getMatchingAttributeSelectHtml() {
        $select = $this->getLayout()->createBlock('core/html_select')
                       ->setName('matching_attribute')
                       ->setId('matching_attribute')
                       ->setTitle(Mage::helper('checkout')->__('Matching Attribute'))
                       ->setClass('validate-select form-control')
                       ->setValue($this->getSupplier()->getUpdaterCsvAttribute())
                       ->setOptions($this->getAttributes());

        return $select->getHtml();
    }

    public function getAttributes() {
        $attributes = Mage::getResourceModel('catalog/product_attribute_collection')
            ->getItems();

        $attributesArray = array();
        foreach ($attributes as $attribute) {
            if(!$attribute->getFrontendLabel()) continue;

            $attributesArray[$attribute->getAttributecode()] = $attribute->getFrontendLabel();
        }

        return $attributesArray;
    }
}