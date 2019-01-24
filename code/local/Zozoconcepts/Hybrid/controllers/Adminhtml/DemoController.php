<?php 
    class Zozoconcepts_Hybrid_Adminhtml_DemoController extends Mage_Adminhtml_Controller_Action{
        public function indexAction() {
            $this->getResponse()->setRedirect($this->getUrl("adminhtml/system_config/edit/section/hybrid/"));
        }
        public function importAction() {
            $refererUrl = $this->_getRefererUrl();
            if(empty($refererUrl)){
                $refererUrl = $this->getUrl("adminhtml/system_config/edit/section/hybrid/");
            }
            $demoversion = $this->getRequest()->getParam('demoversion');
            $website = $this->getRequest()->getParam('website');
            $store   = $this->getRequest()->getParam('store');
            Mage::getSingleton('hybrid/import_demoversion')->importDemoversion($demoversion,$store,$website);
            Mage::getSingleton('hybrid/cssgen_generator')->generateCss('hybrid_settings', $website, $store);
            Mage::getSingleton('hybrid/cssgen_generator')->generateCss('hybrid_design', $website, $store);
            $this->getResponse()->setRedirect($refererUrl);
        }
    }
?>