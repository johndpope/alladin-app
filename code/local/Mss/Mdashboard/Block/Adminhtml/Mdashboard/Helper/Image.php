<?php

class Mss_Mdashboard_Block_Adminhtml_Mdashboard_Helper_Image
    extends Varien_Data_Form_Element_Image {

    protected function _getUrl(){
        $url = false;
        if ($this->getValue()) {
			$path =   Mage::helper('mdashboard')->reImageName($this->getValue());
            $url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB)."media/mdashboard/".$path;
        }
        return $url;
    }
}
