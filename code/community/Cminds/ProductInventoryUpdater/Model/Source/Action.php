<?php
class Cminds_ProductInventoryUpdater_Model_Source_Action
    extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{

    const DO_NOTHING = 0;
    const SET_OUT_OF_STOCK = 1;

    public function getAllOptions() {

        $options = array(
            array('value' => self::DO_NOTHING, 'label' => Mage::helper('productinventoryupdater')->__('Do nothing')),
            array('value' => self::SET_OUT_OF_STOCK, 'label' => Mage::helper('productinventoryupdater')->__('Set out of Stock'))
        );

        foreach ($options as $option) {
            $this->_options[] = array('label'=> $option['label'], 'value' => $option['value']);
        }

        return $this->_options;
    }

    public function toOptionArray() {
        return $this->getAllOptions();
    }
}