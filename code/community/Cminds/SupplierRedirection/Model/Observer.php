<?php
class Cminds_SupplierRedirection_Model_Observer {
    public function controllerActionPredispatch($observer) {
        $controllerAction = $observer->getControllerAction();
        $server = $controllerAction->getRequest()->getServer();

        $c = Mage::getModel('customer/customer')
                 ->getCollection()
                 ->addAttributetoSelect('domain_url')
                 ->addAttributetoFilter('domain_url', $server['SERVER_NAME'])
                 ->getFirstItem();

        if($c->getId()) {
            $request = $controllerAction->getRequest();
            $request->setDispatched(false);
            $request->setModuleName('marketplace');
            $request->setControllerName('supplier');
            $request->setActionName('view');
            $request->setParams(array('id' => $c->getId()));
            $request->setControllerModule('Cminds_Marketplace');
        }
    }

    public function navLoad($observer) {
            $event = $observer->getEvent();
            $items = $event->getItems();

            if(Mage::helper('marketplace')->supplierPagesEnabled()) {
                $items['SUPPLIER_DOMAIN'] = [
                    'label'     => 'Domain Settings',
                    'url'   	=> 'supplier/domain/settings',
                    'parent'    => 'SETTINGS',
                    'sort' => -1
                ];
            }

            $observer->getEvent()->setItems($items);
    }
}