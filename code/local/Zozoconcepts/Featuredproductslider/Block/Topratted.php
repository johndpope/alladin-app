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
class Zozoconcepts_Featuredproductslider_Block_Topratted extends Mage_Catalog_Block_Product_Abstract
{
	
	public function getTopratted() { 
 
	$limit = 10; 
 
	// get all visible products
	$_products = Mage::getModel('catalog/product')->getCollection(); 
	$_products->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds());
	$_products->addAttributeToSelect('*')->addStoreFilter();            
		
	$_rating = array(); 
 
	foreach($_products as $_product) {  
	
		$storeId = Mage::app()->getStore()->getId(); 
 
		// get ratings for individual product
		$_productRating = Mage::getModel('review/review_summary') 
							->setStoreId($storeId) 
							->load($_product->getId()); 
		 
		$_rating[] = array(
					 'rating' => $_productRating['rating_summary'], 
					 'product' => $_product                         
					);  			
	} 
 
	// sort in descending order of rating
	arsort($_rating); 
	
	// returning limited number of products and ratings
	return array_slice($_rating, 0, $limit); 
}
}