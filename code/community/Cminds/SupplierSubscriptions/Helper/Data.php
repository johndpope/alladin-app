<?php


class Cminds_SupplierSubscriptions_Helper_Data extends Mage_Core_Helper_Abstract
{
    const CONF_GENERAL_PATH = 'suppliersubscriptions_catalog/general';
    const CONF_REGISTRATION_PATH = 'suppliersubscriptions_catalog/registration';

    /**
     * Returns is module enabled in system configuration.
     *
     * @return bool
     */
    public function isEnabled()
    {
        return (boolean) Mage::getStoreConfig(self::CONF_GENERAL_PATH . '/module_enabled');
    }

    /**
     * Returns default country for registration form.
     *
     * @return mixed
     */

    /**
     * @return Cminds_SupplierRegistrationExtended_Model_Plan|null
     */
    public function getSupplierPlan()
    {
        $supplier = Mage::helper('suppliersubscriptions')->getLoggedSupplier();
        $planId = $supplier->getCurrentPlan();

        /* @var $planModel Cminds_SupplierRegistrationExtended_Model_Plan */
        $planModel = Mage::getModel('suppliersubscriptions/plan')->load((int)$planId);
        if ($planModel->getId()) {
            return $planModel;
        }
        return null;
    }

    /**
     * Return Images per product for current plan.
     * @return int
     */
    public function getSupplierPlanImages()
    {
        $supplierPlan = $this->getSupplierPlan();
        if (!is_null($supplierPlan)) {
            return (int)$supplierPlan->getImagesPerProduct();
        }
        return 0;
    }

    /**
     * Return products count for current plan.
     * @return int
     */
    public function getSupplierPlanProducts()
    {
        $supplierPlan = $this->getSupplierPlan();
        if (!is_null($supplierPlan)) {
            return (int)$supplierPlan->getProductsCount();
        }
        return 0;
    }

    /**
     * Is user has active plan.
     * @return bool
     */
    public function isSupplierPlanActive()
    {
        $supplierPlan = $this->getSupplierPlan();
        if (!is_null($supplierPlan)) {
            $supplier = Mage::helper('suppliersubscriptions')->getLoggedSupplier();
            $planToData = strtotime($supplier->getPlanToDate());
            if ($planToData != false && time() <= $planToData) {
                return true;
            }
        }
        return false;
    }

    /**
     * Return can have banner for current plan.
     * @return boolean
     */

    /**
     * @return array
     */
    public function getSupplierProductsCount() {
        $count = array();

        $collection = Mage::getModel('catalog/product')
            ->getCollection()
            ->addAttributeToSelect('creator_id')
            ->addAttributeToFilter(array(array('attribute' => 'creator_id', 'eq' => Mage::helper('supplierfrontendproductuploader')->getSupplierId())));

        foreach($collection AS $product) {
            $count[] = $product->getId();
        }

        return count($count);
    }

    public function isSupplier($customer_id) {
        $customerGroupConfig = Mage::getStoreConfig('supplierfrontendproductuploader_catalog/supplierfrontendproductuploader_supplier_config/supplier_group_id');
        $editorGroupConfig = Mage::getStoreConfig('supplierfrontendproductuploader_catalog/supplierfrontendproductuploader_supplier_config/editor_group_id');

        $allowedGroups = array();

        if($customerGroupConfig != NULL) {
            $allowedGroups[] = $customerGroupConfig;
        }
        if($editorGroupConfig != NULL) {
            $allowedGroups[] = $editorGroupConfig;
        }
        $customer = Mage::getModel('customer/customer')->load($customer_id);

        $groupId = $customer->getGroupId();

        return in_array($groupId, $allowedGroups);
    }

    public function getLoggedSupplier() {
        $loggedUser = Mage::getSingleton( 'customer/session', array('name' => 'frontend') );
        $c = $loggedUser->getCustomer();
        $customer = Mage::getModel('customer/customer')->load($c->getId());

        return $customer;
    }

    public function getPlanCountsAdmin($creatorId)
    {
        $creator = Mage::getModel('customer/customer')->load($creatorId);
        $planId = $creator->getCurrentPlan();
        $supplierPlan = Mage::getModel('suppliersubscriptions/plan')->load((int)$planId);
        if (!is_null($supplierPlan)) {
            return $supplierPlan->getProductsCount();
        }
    }

    public function getProductCountsAdmin($creatorId)
    {
        $suppliersCount = array();

        $collection = Mage::getModel('catalog/product')
            ->getCollection()
            ->addAttributeToSelect('creator_id')
            ->addAttributeToFilter(array(array('attribute' => 'creator_id', 'eq' => $creatorId)))
            ->addAttributeToFilter(array(array('attribute' => 'frontendproduct_product_status', 'eq' => 1)));

        foreach ($collection AS $product) {
            $suppliersCount[] = $product->getId();
        }
        return count($suppliersCount);
    }
}