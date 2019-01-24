<?php
/**
 * Zozoconcepts_Featuredproductslider extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Zozoconcepts
 * @package        Zozoconcepts_Featuredproductslider
 * @copyright      Copyright (c) 2014
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * 
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Featuredproductslider
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Featuredproductslider_Block_Featuredproductslider extends Mage_Catalog_Block_Product_Abstract
{
	/*public function getConfig($att) 
	{
		$config = Mage::getStoreConfig('hybrid');
		if (isset($config['beforefooter']) ) {
			$value = $config['beforefooter'][$att];
			return $value;
		} else {
			throw new Exception($att.' value not set');
		}
	}*/
	
	function cut_string($string,$number){
		if(strlen($string) <= $number) {
			return $string;
		}
		else {	
			if(strpos($string," ",$number) > $number){
				$new_space = strpos($string," ",$number);
				$new_string = substr($string,0,$new_space)."..";
				return $new_string;
			}
			$new_string = substr($string,0,$number)."..";
			return $new_string;
		}
	}
	
	public function getFeatured()
	{
		$storeId = (int) Mage::app()->getStore()->getId();
		$visibility = array(
		   Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH,
		   Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG
		);
		$_productCollection_model = Mage::getModel('catalog/product')
									->getCollection()
									->setStoreId($storeId)
									->addStoreFilter($storeId)
									->addAttributeToSelect('*')
									->addAttributeToFilter('status', array('eq' => 1))
									->addAttributeToFilter('visibility', $visibility)
									->addAttributeToFilter('featured', 1)
									->load(); 
		return $_productCollection_model;							
	}
	
}