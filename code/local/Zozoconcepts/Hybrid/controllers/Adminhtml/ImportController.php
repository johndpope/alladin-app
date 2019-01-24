<?php

class Zozoconcepts_Hybrid_Adminhtml_ImportController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
	{
		$this->getResponse()->setRedirect($this->getUrl("adminhtml/system_config/edit/section/hybrid/"));

	}
	
	public function blocksAction()
	{
		$overwrite = Mage::helper('hybrid')->getConf('installer/overwrite_blocks');
		Mage::getSingleton('hybrid/import_cms')->importCmsItems('cms/block', 'blocks', $overwrite);
		
		$this->getResponse()->setRedirect($this->getUrl("adminhtml/system_config/edit/section/hybrid/"));
		
	}
	
	public function pagesAction()
	{
		$overwrite = Mage::helper('hybrid')->getConf('installer/overwrite_pages');
		Mage::getSingleton('hybrid/import_cms')->importCmsItems('cms/page', 'pages', $overwrite);
		
		$this->getResponse()->setRedirect($this->getUrl("adminhtml/system_config/edit/section/hybrid/"));
	}
}
