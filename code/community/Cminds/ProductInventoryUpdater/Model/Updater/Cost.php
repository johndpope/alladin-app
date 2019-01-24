<?php

/**
 * Cminds Product Inventory Updater - Stock
 * Updating product stock
 *
 * @category    Cminds
 * @package     Cminds_ProductInventoryUpdater
 * @author      Wojtek Kaminski <wojtek.kaminski@gmail.com>
 */
class Cminds_ProductInventoryUpdater_Model_Updater_Cost
	extends	Cminds_ProductInventoryUpdater_Model_Updater_Csv
	implements Cminds_ProductInventoryUpdater_Model_Updater_Interface
{
	const MATCHING_INDEX = 1;

	protected $_feedUrl;

	protected $_matchingCostIndex;

	protected $_qtyPos;
    protected $changedProducts = array();

	/**
	 * Method ran before method run. It prepares a CSV
	 */
	public function prepare() {
		$this->_feedUrl = $this->getVendor()->getUpdaterCsvLink();
		$this->_matchingIndex = $this->getVendor()->getUpdaterCsvColumn();
		$this->_matchingColumnIndex = $this->getVendor()->getUpdaterCostColumn();
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

		if (!$this->_columnPos) {
			return false;
		}
        $this->changedProducts = array();

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
								  ->addAttributeToSelect("sku")
								  ->addAttributeToSelect("cost")
								  ->addAttributeToFilter(
									  $this->matchingAttribute,
									  $vendorLoadValue
								  );

				$_product = $collection->getFirstItem();

				if (!$_product->getId()) {
					if($this->getLoggingEnabled()){
						throw new Exception( "Product is not available in catalog" );
					}
				}

				if ($_product->getCreatorId() !== $this->getVendor()->getId()) {
					throw new Exception( "Product doesn't belongs to Vendor: " . $this->getVendor()->getName());
				}
				if ($this->_columnPos) {
					$vendorAttributeValue = trim($product[$this->_columnPos]);
					$vendorAttributeValue = str_replace('"', "", $vendorAttributeValue);
					$vendorAttributeValue = (float) str_replace("'", "", $vendorAttributeValue);

                    if (
                        $vendorAttributeValue != $_product->getCost()
                        && $_product->getCost() > 0
                    ) {
                        $this->changedProducts[$this->getVendor()->getId()][] = array(
                            "old_value" => $_product->getCost(),
                            "value" => $vendorAttributeValue,
                            "sku" => $_product->getSku(),
                            "id" => $_product->getId(),
                        );
                    }

					$_product->setCost($vendorAttributeValue);
					$_product->getResource()->saveAttribute($_product, 'cost');
				}
				$productIds[] = $_product->getId();
			} catch (Exception $e) {
				if($this->getLoggingEnabled()) {
					if(isset($product[$this->_matchingPos])) {
						Mage::log( "Cannot update product with matching value" . $product[$this->_matchingPos],
							null,
							'inventory_update.log' );
					}

					Mage::log( "Reason : " . $e->getMessage(), null,
						'inventory_update.log' );
				}
			}
			$i++;
		}

        return $this;
	}

    public function notify() {
        if (!Mage::helper("inventoryupdater")->canNotifyWhenCostChanged()) {
            return $this;
        }

        foreach ($this->changedProducts as $vendor_id => $items) {
            $this->sendEmail($vendor_id, $items);
        }

        return $this;
    }

    protected function sendEmail($vendor_id, $items) {
        $emailTemplate  = Mage::getModel('core/email_template')
                              ->loadDefault('rma_update');


        $vendor = Mage::getModel("customer/customer")->load($vendor_id);

        $emailTemplateVariables = array();
        $emailTemplateVariables['items'] = $items;
        $emailTemplateVariables['vendor'] = $vendor;

        $recipientName = Mage::getStoreConfig('trans_email/ident_general/name');
        $recipientEmail = Mage::getStoreConfig('trans_email/ident_general/email');

        $emailTemplate->setSenderName($recipientName);
        $emailTemplate->setSenderEmail($recipientEmail);

        $emailTemplate->getProcessedTemplate($emailTemplateVariables);
        $emailTemplate->send($recipientEmail, $recipientName, $emailTemplateVariables);
    }
}
