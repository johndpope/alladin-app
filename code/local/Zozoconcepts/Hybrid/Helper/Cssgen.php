<?php 
/*------------------------------------------------------------------------
# zozothemes concept
-------------------------------------------------------------------------*/ 
class Zozoconcepts_Hybrid_Helper_Cssgen extends Mage_Core_Helper_Abstract 
{
	/**
	 * Path and directory of the automatically generated CSS
	 *
	 * @var string
	 */
	protected $_generatedCssFolder;
	protected $_generatedCssPath;
	protected $_generatedCssDir;
	protected $_templatePath;
	
	public function __construct()
	{
		//Create paths
		$this->_generatedCssFolder = 'css/_config/';
		$this->_generatedCssPath = 'frontend/varmo/default/' . $this->_generatedCssFolder;
		$this->_generatedCssDir = Mage::getBaseDir('skin') . '/' . $this->_generatedCssPath;
		$this->_templatePath = 'zozoconcepts/hybrid/css/';
	}
	
	/**
	 * Get directory of automatically generated CSS
	 *
	 * @return string
	 */
	public function getGeneratedCssDir()
    {
        return $this->_generatedCssDir;
    }

	/**
	 * Get path to CSS template
	 *
	 * @return string
	 */
	public function getTemplatePath()
    {
        return $this->_templatePath;
    }

	/**
	 * Get file path: Theme config CSS 
	 *
	 * @return string
	 
	public function getConfigcss()
	{
		return $this->_generatedCssFolder . 'theme_css_' . Mage::app()->getStore()->getCode() . '.css';
	}*/
	
	/**
	 * Get file path: Theme design CSS 
	 *
	 * @return string
	 */
	public function getDesigncss()
	{
		return $this->_generatedCssFolder . 'hybrid_design_' . Mage::app()->getStore()->getCode() . '.css';
	}
	/**
	 * Get file path: Theme settings CSS 
	 *
	 * @return string
	 */
	public function getSettingscss()
	{
		return $this->_generatedCssFolder . 'hybrid_settings_' . Mage::app()->getStore()->getCode() . '.css';
	}
	
}