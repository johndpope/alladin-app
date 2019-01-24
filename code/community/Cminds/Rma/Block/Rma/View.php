<?php
class Cminds_Rma_Block_Rma_View extends Cminds_Rma_Block_Rma_Abstract {
    public function _construct() {
        $this->setTemplate('cminds_rma/view.phtml');
    }

    public function getRma() {
        return Mage::getModel('cminds_rma/rma')->load($this->getRmaId());
    }
}