<?php 
/*------------------------------------------------------------------------
# zozothemes concept
-------------------------------------------------------------------------*/ 
class Zozoconcepts_Hybrid_Helper_Data extends Mage_Core_Helper_Abstract 
{
	/**
	 * Get theme's Configuration settings (single option)
	 *
	 * @return string
	 */
	public function getConf($optionString, $storeCode = NULL)
    {
        return Mage::getStoreConfig('hybrid/' . $optionString, $storeCode);
    }
	
	/**
	 * Get theme's design settings (single option)
	 *
	 * @return string
	 */
	public function getConfDesign($optionString, $storeCode = NULL)
    {
        return Mage::getStoreConfig('hybrid_design/' . $optionString, $storeCode);
    }
	
	/**
	 * Get theme's layout settings (single option)
	 *
	 * @return string
	 */
	public function getConfLayout($optionString, $storeCode = NULL)
    {
        return Mage::getStoreConfig('hybrid_layout/' . $optionString, $storeCode);
    }
	/**
	 * Get Icon sets for links and buttons as configured 
	 *
	 * @return string
	 */
	public function getIcon ($type) {
		return '<i class="fa '. Mage::getStoreConfig('hybrid_design/icons/'. $type) .'"></i>';
    }
	
	public function cut_string($string,$number){
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
	public function getHeaderTemplates($storeCode = NULL){
		return Mage::getStoreConfig('hybrid/header/type', $storeCode);
	}
	public function getPreviousProduct()
    {
        $_prev_prod = NULL;
        $_product_id = Mage::registry('current_product')->getId();

        $cat = Mage::registry('current_category');
        if($cat) {
            $category_products = $cat->getProductCollection()->addAttributeToSort('position', 'asc');
            Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($category_products);
            Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($category_products);

            $store = Mage::app()->getStore();
            $code = $store->getCode();
            if (!Mage::getStoreConfig("cataloginventory/options/show_out_of_stock", $code))
                Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($category_products);

            $items = $category_products->getItems();
            $cat_prod_ids = (array_keys($items));

            $_pos = array_search($_product_id, $cat_prod_ids); // get position of current product

            // get the next product url
            if (isset($cat_prod_ids[$_pos - 1])) {
                $_prev_prod = Mage::getModel('catalog/product')->load($cat_prod_ids[$_pos - 1]);
            } else {
                return false;
            }
        }
        if($_prev_prod != NULL){
            return $_prev_prod;
        } else {
            return false;
        }
 
    }
 
 
    public function getNextProduct()
    {
        $_next_prod = NULL;
        $_product_id = Mage::registry('current_product')->getId();

        $cat = Mage::registry('current_category');

        if($cat) {
            $category_products = $cat->getProductCollection()->addAttributeToSort('position', 'asc');
            Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($category_products);
            Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($category_products);

            $store = Mage::app()->getStore();
            $code = $store->getCode();
            if (!Mage::getStoreConfig("cataloginventory/options/show_out_of_stock", $code))
                Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($category_products);

            $items = $category_products->getItems();
            $cat_prod_ids = (array_keys($items));

            $_pos = array_search($_product_id, $cat_prod_ids); // get position of current product

            // get the next product url
            if (isset($cat_prod_ids[$_pos + 1])) {
                $_next_prod = Mage::getModel('catalog/product')->load($cat_prod_ids[$_pos + 1]);
            } else {
                return false;
            }
        }

        if($_next_prod != NULL){
            return $_next_prod;
        } else {
            return false;
        }
    }
	public function getWishlistUrl(){
		return Mage::getUrl('wishlist');
	}
}