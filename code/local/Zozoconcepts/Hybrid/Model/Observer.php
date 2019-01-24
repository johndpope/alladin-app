<?php
/*------------------------------------------------------------------------
# zozothemes concept
-------------------------------------------------------------------------*/ 
/**
 * Call actions after configuration is saved
 */
class Zozoconcepts_Hybrid_Model_Observer
{
	/**
     * After any system config is saved
     */
	public function hookTo_controllerActionPostdispatchAdminhtmlSystemConfigSave()
	{
		$section = Mage::app()->getRequest()->getParam('section');
		if ($section == 'hybrid')
		{
			$websiteCode = Mage::app()->getRequest()->getParam('website');
			$storeCode = Mage::app()->getRequest()->getParam('store');
		
			Mage::getSingleton('hybrid/cssgen_generator')->generateCss('hybrid_settings', $websiteCode, $storeCode);
		}
		elseif ($section == 'hybrid_design')
		{
			$websiteCode = Mage::app()->getRequest()->getParam('website');
			$storeCode = Mage::app()->getRequest()->getParam('store');
			
			Mage::getSingleton('hybrid/cssgen_generator')->generateCss('hybrid_design', $websiteCode, $storeCode);
		}
	}
	
	/**
     * After store view is saved
     */
	public function hookTo_storeEdit(Varien_Event_Observer $observer)
	{
		$store = $observer->getEvent()->getStore();
		$storeCode = $store->getCode();
		$websiteCode = $store->getWebsite()->getCode();
		
		Mage::getSingleton('porto/cssconfig_generator')->generateCss('hybrid_settings', $websiteCode, $storeCode);
		Mage::getSingleton('porto/cssconfig_generator')->generateCss('hybrid_design', $websiteCode, $storeCode);
	}
}
