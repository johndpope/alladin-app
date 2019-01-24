<?php
/**
 * @category  Apptrian
 * @package   Apptrian_FacebookPixel
 * @author    Apptrian
 * @copyright Copyright (c) 2017 Apptrian (http://www.apptrian.com)
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License
 */
class Apptrian_FacebookPixel_Block_Code extends Mage_Core_Block_Template
{
    /**
     * Used in code.phtml and returns needed data.
     *
     * @return array
     */
    public function getFacebookPixelData()
    {
        $data = array();
        
        $data['id'] = Mage::getStoreConfig(
            'apptrian_facebookpixel/general/pixel_id'
        );
        
        $data['full_action_name'] = Mage::app()->getFrontController()
            ->getAction()->getFullActionName();
        
        return $data;
    }
    
    /**
     * Returns product data needed for dynamic ads tracking.
     *
     * @return array
     */
    public function getProductData()
    {
        $p = Mage::registry('current_product');
    
        $data = array();
    
        $data['content_name']     = $this->escapeSingleQuotes($p->getName());
        $data['content_ids']      = $this->escapeSingleQuotes($p->getSku());
        $data['content_type']     = 'product';
        $data['value']            = number_format(
            $this->getCalculatedPrice(),
            2,
            '.',
            ''
        );
        $data['currency']         = $this->getCurrencyCode();
    
        return $data;
    }
    
    /**
     * Returns data needed for purchase tracking.
     *
     * @return array|null
     */
    public function getOrderData()
    {
        $orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
        
        if ($orderId) {
            $order   = Mage::getModel('sales/order')->load($orderId);
    
            $items = array();
    
            foreach ($order->getAllVisibleItems() as $item) {
                $items[] = array(
                        'name' => $item->getName(), 'sku' => $item->getSku()
                );
            }
    
            $data = array();
    
            if (count($items) === 1) {
                $data['content_name'] = $this->escapeSingleQuotes(
                    $items[0]['name']
                );
            }
    
            $ids = '';
            foreach ($items as $i) {
                $ids .= "'" . $this->escapeSingleQuotes($i['sku']) . "', ";
            }
    
            $data['content_ids']  = trim($ids, ", ");
            $data['content_type'] = 'product';
            $data['value']        = number_format(
                $order->getGrandTotal(),
                2,
                '.',
                ''
            );
            $data['currency']     = $order->getOrderCurrencyCode();
    
            return $data;
        } else {
            return null;
        }
    }
    
    /**
     * Returns product calculated price depending option selected in
     * Stores > Cofiguration > Sales > Tax > Price Display Settings
     * If "Excluding Tax" is selected price will not include tax.
     * If "Including Tax" or "Including and Excluding Tax" is selected price
     * will include tax.
     *
     * @return int|float|string
     */
    public function getCalculatedPrice()
    {
        $p = Mage::registry('current_product');
    
        $productType = $p->getTypeId();
    
        $calculatedPrice = 0;
    
        // Tax Display
        // 1 - excluding tax
        // 2 - including tax
        // 3 - including and excluding tax
        $tax = (int) Mage::getStoreConfig('tax/display/type');
    
        if ($productType == 'configurable') {
            if ($tax === 1) {
                $calculatedPrice = $p->getFinalPrice();
            } else {
                $calculatedPrice = Mage::helper('tax')
                    ->getPrice($p, $p->getFinalPrice());
            }
        } elseif ($productType == 'grouped') {
            $associatedProducts = $p->getTypeInstance(true)
                ->getAssociatedProducts($p);
    
            $prices = array();
    
            foreach ($associatedProducts as $associatedProduct) {
                $prices[] = $associatedProduct->getPrice();
            }
    
            if (!empty($prices)) {
                $calculatedPrice = min($prices);
            }
    
        // downloadable, simple, virtual
        } else {
            if ($tax === 1) {
                $calculatedPrice = $p->getFinalPrice();
            } else {
                $calculatedPrice = Mage::helper('tax')
                    ->getPrice($p, $p->getFinalPrice());
            }
        }
    
        return $calculatedPrice;
    }
    
    /**
     * Returns 3 letter currency code like USD, GBP, EUR, etc.
     *
     * @return string
     */
    public function getCurrencyCode()
    {
        return strtoupper(Mage::app()->getStore()->getCurrentCurrencyCode());
    }
    
    /**
     * Add slashes to string and prepares string for javascript.
     *
     * @param string $str
     * @return string
     */
    public function escapeSingleQuotes($str)
    {
        return str_replace("'", "\'", $str);
    }
}
