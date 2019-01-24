<?php
class Cminds_Rma_Model_Rma_Opened extends Mage_Core_Model_Abstract
{
    const NO = 0;
    const YES = 1;

    public function getAllOptions() {
        $this->_options = array(
            array('label' => 'No', 'value' => self::NO),
            array('label' => 'Yes', 'value' => self::YES)
        );

        return $this->_options;
    }

    public function toOptionArray() {
        return $this->getAllOptions();
    }
}
