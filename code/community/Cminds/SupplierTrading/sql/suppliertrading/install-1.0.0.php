<?php
$installer = $this;
$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('suppliertrading/trades'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Id')
    ->addColumn('supplier_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable'  => false,
    ), 'SUPPLIER ID')
    ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable'  => false,
    ), 'PRODUCT ID')
    ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable'  => false,
    ), 'CUSTOMER ID')
    ->addColumn('price', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(5,2), array(
        'nullable'  => false,
    ))
    ->addColumn('qty', Varien_Db_Ddl_Table::TYPE_SMALLINT, 1, array(
        'nullable'  => false,
    ))
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_SMALLINT, 1, array(
        'nullable'  => false,
    ))
    ->addColumn('created_on', Varien_Db_Ddl_Table::TYPE_DATETIME, 1, array(
        'nullable'  => false,
    ))->addColumn('note', Varien_Db_Ddl_Table::TYPE_TEXT, '', array(
        'nullable'  => false,
    ));
$installer->getConnection()->createTable($table);

$entity = $this->getEntityTypeId('catalog_product');

$this->addAttribute($entity, 'is_tradeable', array(
    'type' => 'int',
    'input'  => 'select',
    'backend' => '',
    'label'     => 'Tradeable',
    'group'      => 'General',
    'frontend' => '',
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'source'  => 'eav/entity_attribute_source_boolean',
    'visible' => true,
    'required' => false,
    'user_defined' => true,
    'default' => 0,
    'searchable' => false,
    'filterable' => false,
    'comparable' => false,
    'visible_on_front' => false,
    'visible_in_advanced_search' => false,
));

$installer->endSetup();

$write   = Mage::getSingleton('core/resource')->getConnection('core_write');
$table   = Mage::getSingleton('core/resource')->getTableName('eav/attribute');

$write->query("UPDATE {$table} SET available_for_supplier = 1 WHERE attribute_code = 'is_tradeable'");