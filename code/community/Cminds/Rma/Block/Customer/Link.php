<?php

class Cminds_Rma_Block_Customer_Link extends Mage_Core_Block_Abstract
{

    public function addLinkToParentBlock()
    {
        /**
         * @var Cminds_Rma_Helper_Data $dataHelper
        */
        $dataHelper = Mage::helper("cminds_rma");
        $parent = $this->getParentBlock();
        if ($parent) {
            if ($dataHelper->isEnabled()) {
                $parent->addLink(
                    $this->getLinkTitle(),
                    $this->getLinkUrl(),
                    $this->getLinkTitle()
                );

            }
        }
    }

    public function getLinkTitle()
    {
        return $this->__("RMA");
    }

    public function getLinkUrl()
    {
        return "cminds_rma/rma/list";
    }
}