<?php

/**
 * Cminds Product Inventory Updater - Stock
 * Updating product stock
 *
 * @category    Cminds
 * @package     Cminds_ProductInventoryUpdater
 * @author      Wojtek Kaminski <wojtek.kaminski@gmail.com>
 */
class Cminds_ProductInventoryUpdater_Model_Updater_Stock
	extends	Cminds_ProductInventoryUpdater_Model_Updater_Csv
	implements Cminds_ProductInventoryUpdater_Model_Updater_Interface
{
	const MATCHING_INDEX = 1;

	protected $matchingAttribute;

	/**
	 * Method ran before method run. It prepares a CSV
	 */
	public function prepare() {
		$this->_feedUrl = $this->getVendor()->getUpdaterCsvLink();
		$this->_matchingIndex = $this->getVendor()->getUpdaterCsvColumn();
		$this->_matchingColumnIndex = $this->getVendor()->getUpdaterQtyColumn();
		$this->matchingAttribute = $this->getVendor()->getUpdaterCsvAttribute();
		$this->delimiter = $this->getVendor()->getUpdaterCsvDelimiter();

		$this->parse();
	}

	/**
	 * Main method, runs import, matching products and update their stock
	 */
	public function run()
	{
		$products = array();
		$products['ids'] = array();
		$i = 0;
		$productIds = array();
		foreach($this->getParsedData() AS $product) {
			if($i == 0){
				$i++;
				continue;
			}
			try {
				if(
					!isset($product[$this->_matchingPos])
					|| !$product[$this->_matchingPos]
				) {

					if($this->getLoggingEnabled()) {
						throw new Exception( $this->_matchingIndex . " is empty or does not exists" );
					}
				}

				$vendorLoadValue = trim($product[$this->_matchingPos]);
				$vendorLoadValue = str_replace('"', "", $vendorLoadValue);
				$vendorLoadValue = str_replace("'", "", $vendorLoadValue);

				$collection = Mage::getModel("catalog/product")
								  ->getCollection()
								  ->addAttributeToSelect("creator_id")
								  ->addAttributeToFilter(
									  $this->matchingAttribute,
									  $vendorLoadValue
								  );

				$_product = $collection->getFirstItem();

				if(!$_product->getId()){
					if($this->getLoggingEnabled()){
						throw new Exception( "Product is not available in catalog" );
					}
				}

				if ($_product->getCreatorId() !== $this->getVendor()->getId()) {
					throw new Exception( "Product doesn't belongs to Vendor: " . $this->getVendor()->getName());
				}

				if($this->_columnPos) {
					$p = Mage::getModel("catalog/product")->load($_product->getId());
					$stockData = $p->getStockData();

					$qty = $product[ $this->_columnPos ];
					$qty = str_replace('"', "", $product[$this->_columnPos]);

					if ( ! is_numeric( $qty ) ) {
						if ( strtolower( $qty ) === "Y" || strtolower( $qty ) === "Yes" ) {
							$qty = 9999;
						} else {
							$qty = 0;
						}
					}

					$stockData['qty']         = $qty;
					$stockData['is_in_stock'] = ( $product[ $this->_columnPos ] > 0 ) ? 1 : 0;

					$p->setStockData( $stockData );
					$p->save();
				}
				$productIds[] = $_product->getId();
			} catch(Exception $e) {
				if($this->getLoggingEnabled()) {
					if(isset($product[$this->_matchingPos])) {
						Mage::log( "Cannot update product with matching value " . $product[$this->_matchingPos],
							null,
							'inventory_update.log' );
					}

					Mage::log( "Reason : " . $e->getMessage(), null,
						'inventory_update.log' );
				}
			}
			$i++;
		}

		if($this->getVendor()->getUpdaterCsvAction()) {
			$collection = Mage::getModel("catalog/product")
							  ->getCollection()
							  ->addAttributeToSelect('creator_id')
							  ->addAttributeToFilter(
								  'creator_id',
								  array(
									  'eq' => $this->getVendor()->getId()
								  )
							  );
			if($productIds) {
				$collection->addAttributeToFilter(
					'entity_id',
					array( 'nin' => $productIds )
				);
			} else {
				$collection->addAttributeToFilter('entity_id', 0);
			}

			foreach($collection as $product) {
				$_productModel = Mage::getModel("catalog/product")->load($product->getId());
				$stockData = array(
					'is_in_stock' => 0,
					'qty' => 0,
					'manage_stock' => 1,
					'use_config_notify_stock_qty' => 1
				);
				$_productModel->setStockData($stockData);
				$_productModel->save();
			}
		}
	}
}
