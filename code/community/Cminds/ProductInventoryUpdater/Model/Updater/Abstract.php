<?php

/**
 * Cminds Product Inventory Updater Abstract Updater Model
 * Added here any method that can be used globally in all import types
 *
 * @category    Cminds
 * @package     Cminds_ProductInventoryUpdater
 * @author      Wojtek Kaminski <wojtek.kaminski@gmail.com>
 */
abstract class Cminds_ProductInventoryUpdater_Model_Updater_Abstract
	extends	Varien_Object
{
	/**
	 * Method load product object
	 *
	 * @param $value
	 * @param string $productAttr
	 *
	 * @return Mage_Core_Model_Abstract
	 */
	public function loadProduct($value, $productAttr = 'entity_id') {
		return Mage::getModel('catalog/product')->load($value, $productAttr);
	}

	public function notify() {
		return $this;
	}
}
