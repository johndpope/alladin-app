<?php

class Cminds_SupplierSubscriptions_Block_Plan_List
    extends Mage_Core_Block_Template
{

    /**
     * Returns filtered and sorted plans as collection of products with plans model included.
     *
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    public function getProductPlanCollection()
    {
        /* @var $productCollection Mage_Catalog_Model_Resource_Product_Collection */
        $productCollection = Mage::getModel('catalog/product')->getCollection();
        $productCollection->getSelect()
            ->joinInner(array(
                'plan' => Mage::getSingleton('core/resource')->getTableName('suppliersubscriptions/plans')
            ), 'e.entity_id=plan.product_id', 'plan.id as plan_id')
//            ->order('plan.listing_priority ASC')
        ;

        /* @var $product Mage_Catalog_Model_Product */
        foreach ($productCollection as $product) {
            /* @var $planModel Cminds_SupplierRegistrationExtended_Model_Plan */
            $planModel = Mage::getModel('suppliersubscriptions/plan')->load($product->getPlanId());
            if (!$planModel->getId()) {
                $productCollection->removeItemByKey($product->getId());
                continue;
            }
            $product->load($product->getId());
            $product->setData('plan', $planModel);
        }

        return $productCollection;
    }

    /**
     * Returns action link for upgrade form.
     *
     * @return string
     */
    public function getActionUrl()
    {
        return Mage::getUrl('supplier/plan/buy');
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     * @param int $size
     * @return string
     */
    public function getHash(Mage_Catalog_Model_Product $product, $size = 5)
    {
        return substr(md5($product->getSku()), 0, $size);
    }

    /**
     * @return array
     */
    public function getPlansFeatures()
    {
        return array(
            'products_count'        => $this->__('Amount of the products'),
           // 'banner'                => $this->__('Banner'),
            'images_per_product'    => $this->__('Images Per Products'),
          //  'push_messages'         => $this->__('Push Messages'),
//            'listing_priority'      => $this->__('Priority Listing'),
            'price'                 => '',
        );
    }

}