<?php 
class Cminds_SupplierTrading_Model_Observer extends Mage_Core_Model_Abstract
{
    public function navLoad($observer)
    {
        $dataHelper = Mage::helper("suppliertrading");

        if (!$dataHelper->isEnabled()) {
            return;
        }

        $event = $observer->getEvent();
        $items = $event->getItems();

        $items['TRADES'] =  [
            'label'     => 'Trades',
            'url'       => 'suppliertrading/trade/trades',
            'parent'    => null,
            'action_names' => [
                'cminds_suppliertrading_trade_trades',
            ],
            'sort'     => 4.6
        ];

        $observer->getEvent()->setItems($items);
    }

    /**
     * Add trade form to product page.
     *
     * @return $this
     */
    public function blockToHtmlBefore()
    {
        if (!Mage::registry('current_product')) {
            return $this;
        }

        $helper = Mage::helper('suppliertrading');

        if (Mage::app()->getRequest()->getModuleName() == 'review') {
            return $this;
        }

        if (!$helper->isEnabled()) {
            return $this;
        }

        $layout = Mage::app()->getLayout();
        $frontendFormBlock = $layout->createBlock('suppliertrading/product_price_suggest', 'price_suggest');
        $frontendFormBlock->setTemplate('cminds/suppliertrading/product/price/suggest.phtml');

        $childs = $layout->getBlock('product.info')->getChild('alert_urls');
        if (!empty($childs)) {
            $childBlocks = $childs->getSortedChildren();

            $appendBlock = true;
            if (in_array('price_suggest', $childBlocks)) {
                $appendBlock = false;
            }

            if ($appendBlock) {
                $layout->getBlock('product.info')->getChild('alert_urls')->append($frontendFormBlock);
            }
        }

        return $this;
    }
}