<?php class Zozoconcepts_Hybrid_Model_Cssgen_Generator extends Mage_Core_Model_Abstract{
	
	public function __construct()
	{
		parent::__construct(); 
	}
	 
	public function generateCss($csscode, $websitecode, $storeCode)
	{
		if ($websitecode)
		{
			 if ($storeCode) 
			 {
				 $this->_generateStoreCss($csscode, $storeCode); 
			 }
			 else
			 {
				 $this->_generateWebsiteCss($csscode, $websitecode); }
		}
		else
		{
			$defaultstorecode = Mage::app()->getWebsites(false, true);
			foreach ($defaultstorecode as $dfs_key => $dfs_val) {$this->_generateWebsiteCss($csscode, $dfs_key); }
		} 
	} 
	protected function _generateWebsiteCss($csscode, $websitecode) 
	{
		$dfs_val = Mage::app()->getWebsite($websitecode);
		foreach ($dfs_val->getStoreCodes() as $dfs_key)
		{ 
			$this->_generateStoreCss($csscode, $dfs_key);
		} 
	} 
	protected function _generateStoreCss($csscode, $storeCode)
	{
		if (!Mage::app()->getStore($storeCode)->getIsActive()) 
		return;
		$store_concat = '_' . $storeCode;
		$cssfilename = $csscode . $store_concat . '.css';
		$csspathname = Mage::helper('hybrid/cssgen')->getGeneratedCssDir() . $cssfilename;
		$templatepathname = Mage::helper('hybrid/cssgen')->getTemplatePath() . $csscode . '.phtml';
		Mage::register('cssgen_store', $storeCode);
		try{ 
			$cssBlock = Mage::app()->getLayout()->createBlock('core/template')->setData('area', 'frontend')->setTemplate($templatepathname)->toHtml();
			if (empty($cssBlock)) 
			{
				throw new Exception( Mage::helper('hybrid')->__("Template file is empty or doesn't exist: %s", $templatepathname) ); 
			}
			$varien_io_file = new Varien_Io_File(); 
			$varien_io_file->setAllowCreateFolders(true); 
			$varien_io_file->open(array( 'path' => Mage::helper('hybrid/cssgen')->getGeneratedCssDir() )); 
			$varien_io_file->streamOpen($csspathname, 'w+'); 
			$varien_io_file->streamLock(true); 
			$varien_io_file->streamWrite($cssBlock); 
			$varien_io_file->streamUnlock(); 
			$varien_io_file->streamClose(); 
			}
		catch (Exception $e)
			{ 
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('hybrid')->__('Failed generating CSS file: %s in %s', $cssfilename, Mage::helper('hybrid/cssgen')->getGeneratedCssDir()). '<br/>Message: ' . $e->getMessage()); 
			Mage::logException($e);
			}
			Mage::unregister('cssgen_store'); 
		} 
		}