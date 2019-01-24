<?php
class Cminds_SupplierTrading_Block_Trades extends Mage_Core_Block_Template
{
    public function _construct()
    {
        parent::_construct();
    }

    public function getItems() {
        $supplier_id = Mage::helper('supplierfrontendproductuploader')->getSupplierId();
        $eavAttribute   = new Mage_Eav_Model_Mysql4_Entity_Attribute();
        $code           = $eavAttribute->getIdByCode('catalog_product', 'name');
        $status          = $this->getRequest()->getParam('status',0);

        $collection = Mage::getModel('suppliertrading/trades')
            ->getCollection();

        $collection->getSelect()
            ->joinInner(array('p' => Mage::getSingleton('core/resource')->getTableName('catalog_product_entity_varchar')),
                'p.entity_id = main_table.product_id AND p.attribute_id = ' . $code, array('value as name')
            )
            ->joinInner(array('c' => Mage::getSingleton('core/resource')->getTableName('customer_entity')), 'c.entity_id = main_table.customer_id', array('email') )
            ->where('main_table.status = ?', $status)
            ->where('main_table.supplier_id = ?', $supplier_id)
            ->group('main_table.id')
        ;


        return $collection;
    }

}
