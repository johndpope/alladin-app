<?php

class Cminds_SupplierRedirection_DomainController extends Cminds_Supplierfrontendproductuploader_Controller_Action {
    public function settingsAction() {

        /** @var Cminds_Marketplace_Helper_Data $dataHelper */
        $dataHelper = Mage::helper("marketplace");
        $supplier = $dataHelper->getLoggedSupplier();

        if(!$supplier->getId()) {
            $this->norouteAction();
            return;
        }

        $this->_renderBlocks();
    }

    public function settingsPostAction(){
        /** @var Cminds_Marketplace_Helper_Data $dataHelper */
        $dataHelper = Mage::helper("marketplace");
        $supplier = $dataHelper->getLoggedSupplier();


        if(!$supplier->getId()) {
            $this->norouteAction();
            return;
        }

        $postData = $this->getRequest()->getPost();


        $collection = Mage::getModel("customer/customer")
            ->getCollection()
            ->addAttributeToFilter("domain_url", $postData['domain'])
            ->addAttributeToFilter("entity_id", array("neq" => $supplier->getId()));

        if($collection->getSize() > 0) {
            Mage::getSingleton('core/session')->addError($this->__("This domain is alread in use"));
            $this->getResponse()->setRedirect(Mage::getUrl("*/*/settings"));
        } else {
            $supplier->setDomainUrl($postData['domain']);
            $supplier->save();

            Mage::getSingleton('core/session')->addSuccess($this->__("Domain has been saved"));
            $this->getResponse()->setRedirect(Mage::getUrl("*/*/settings"));
        }
    }
}