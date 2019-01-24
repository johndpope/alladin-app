<?php

class Mss_Mdashboard_IndexController extends Mage_Core_Controller_Front_Action
{

    public function _construct()
    {

        header('content-type: application/json; charset=utf-8');
        header("access-control-allow-origin: *");
        // Mage::helper('connector')->loadParent(Mage::app()->getFrontController()->getRequest()->getHeader('token'));
        parent::_construct();

    }

    public function indexAction()
    {
        $bannerCollection = Mage::getModel('mdashboard/mdashboard')->getCollection();
        $bannerCollection->addFieldToFilter('status', '1');
        $bannerCollection->setOrder('order_banner', 'Asc');

        if ($bannerCollection):
            $result = array();
            foreach ($bannerCollection as $key => $value) {
                $value->setImage(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA)."mdashboard/".Mage::helper('mdashboard')->reImageName($value->getImage()));
                $result[] = $value->getData();
            }
            
            echo json_encode(array('status' => 'success', 'data' => $result));
            exit;
        else:
            echo json_encode(array('status' => 'error', 'message' => $this->__('No banner uploaded')));
        endif;
    }
}
