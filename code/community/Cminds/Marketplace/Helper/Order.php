<?php

class Cminds_Marketplace_Helper_Order extends Mage_Core_Helper_Abstract
{
    public function isSingleVendor($order, $vendor_id)
    {
        $orderItems = $order->getAllItems();
        $dataHelper = Mage::helper("marketplace");

        foreach ($orderItems as $item) {
            if ((int) $dataHelper->getProductSupplierId($item->getProduct()) !== (int) $vendor_id) {
                return false;
            }
        }

        return true;
    }

    public function isVendorOrder($order, $vendor_id)
    {
        $orderItems = $order->getAllItems();
        $dataHelper = Mage::helper("marketplace");

        foreach ($orderItems as $item) {
            if ((int) $dataHelper->getProductSupplierId($item->getProduct()) === (int) $vendor_id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Calculating supplier's income by products from one order.
     *
     * @param $order
     *
     * @return int
     */
    public function calculateIncome($order)
    {
        $income = 0;
        foreach ($order->getAllItems() as $item) {
            if (Mage::helper('marketplace')->isOwner($item->getProductId())) {
                $income += $item->getVendorIncome() * $item->getQtyOrdered();
            }
        }

        return $income;
    }

    /**
     * Calculating supplier's income by all products.
     *
     * @param $supplier
     *
     * @return int
     */
    public function calculateNetIncomeAllProducts($supplier)
    {
        $supplierProductCollection = Mage::getModel('catalog/product')
            ->getCollection()
            ->addAttributeToFilter('creator_id', array('eq' => $supplier));
        $income = 0;
        $productItems = array();
        // get all prodcut ids
        foreach ($supplierProductCollection as $item) {
            if(!in_array($item->getId(), $productItems))
                $productItems[] = $item->getId();
        }
        if(count($productItems)) {
            // get all orders
            $orders = Mage::getModel('sales/order_item')
                ->getCollection()
                ->addFieldToFilter('product_id', array('in', $productItems));

            if($orders){
                foreach ($orders as $order) {
                    $income += $order->getVendorIncome() * $order->getQtyOrdered();
                }
            }
        }

        return $income;
    }

    /**
     * Calculating supplier's income by order's products.
     *
     * @param $supplierId
     *
     * @param $orderId
     *
     * @return float
     */
    public function calcSuppliersNetIncome($supplierId, $orderId)
    {
        $order = Mage::getModel('sales/order')->load($orderId);
        $income = 0;
        foreach ($order->getAllItems() as $item) {
            $item->getVendorIncome();

            $productsSupplier = Mage::helper('marketplace')->getSupplierIdByProductId($item->getProductId());
            if ($productsSupplier == $supplierId) {
                $income += $item->getVendorIncome() * $item->getQtyOrdered();
            }
        }
        return $income;
    }

    /**
     * Calculating supplier's income by order's products with discount.
     *
     * @param $supplierId
     *
     * @param $orderId
     *
     * @return float
     */
    public function calcSuppliersNetIncomeDiscount($supplierId, $orderId)
    {
        $order = Mage::getModel('sales/order')->load($orderId);
        $income = 0;
        foreach ($order->getAllItems() as $item) {
            $item->getVendorIncome();

            $productsSupplier = Mage::helper('marketplace')->getSupplierIdByProductId($item->getProductId());
            if ($productsSupplier == $supplierId) {
                $income += ($item->getVendorIncome()-  $item->getDiscountAmount()* (1-$item->getVendorIncome()/$item->getPrice()))  * $item->getQtyOrdered();
            }
        }
        return $income;
    }




    /**
     * Calculating supplier's total price by order's products.
     *
     * @param $supplierId
     *
     * @param $orderId
     *
     * @return float
     */
    public function calcSuppliersTotalPrice($supplierId, $orderId)
    {
        $helper = Mage::helper('marketplace');

        if ($helper->isBillingReportInclTax() == Cminds_Marketplace_Model_Config_Source_Billing_Calculation::INCL_TAX) {
            $flag = true;
        } else {
            $flag = false;
        }

        $order = Mage::getModel('sales/order')->load($orderId);
        $total = 0;
        foreach ($order->getAllItems() as $item) {

            if ($flag) {
                $price = $item->getRowTotalInclTax();
            } else {
                $price = $item->getRowTotal();
            }

            $productsSupplier = Mage::helper('marketplace')
                ->getSupplierIdByProductId($item->getProductId());

            if ($productsSupplier == $supplierId) {
                $total += $price * $item->getQtyOrdered();
            }
        }
        return $total;
    }

}
