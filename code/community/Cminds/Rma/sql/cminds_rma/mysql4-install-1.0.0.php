<?php
$installer = $this;
$installer->startSetup();

$exists = (boolean) Mage::getSingleton('core/resource')
    ->getConnection('core_write')
    ->showTableStatus(trim($installer->getTable('cminds_rma/rma_entity'),'`'));

if(!$exists) {
    $table = $installer->getConnection()
        ->newTable($installer->getTable('cminds_rma/rma_entity'))
        ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity'  => true,
            'unsigned'  => true,
            'nullable'  => false,
            'primary'   => true,
        ), 'Id')
        ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'nullable'  => false,
        ), 'customer ID')
        ->addColumn('autoincrement_id', Varien_Db_Ddl_Table::TYPE_VARCHAR, 32, array(
            'nullable'  => false,
        ), 'Auto Increment ID')
        ->addColumn('status_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'nullable'  => false,
            'default' => 1,
        ), 'status ID')
        ->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'nullable'  => false,
        ), 'order ID')
        ->addColumn('is_package_opened', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'nullable'  => false,
        ), 'Is package opened')
        ->addColumn('type_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'nullable'  => false,
        ), 'Request Type ID')
        ->addColumn('additional_information', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
            'nullable'  => true,
        ), 'Additional Information')
        ->addColumn('reason_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'nullable'  => false,
        ), 'reason')
        ->addColumn('agreed_policy', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'nullable'  => false,
        ), 'reason')
        ->addColumn('amount', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(5,2), array(
            'nullable'  => false,
        ))
        ->addColumn('is_closed', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'nullable'  => false,
            'default' => 0,
        ), 'is closed')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
            'nullable'  => false,
        ));
    $installer->getConnection()->createTable($table);
}
$exists = (boolean) Mage::getSingleton('core/resource')
    ->getConnection('core_write')
    ->showTableStatus(trim($installer->getTable('cminds_rma/rma_item'),'`'));

if(!$exists) {
    $table = $installer->getConnection()
        ->newTable($installer->getTable('cminds_rma/rma_item'))
        ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity'  => true,
            'unsigned'  => true,
            'nullable'  => false,
            'primary'   => true,
        ), 'Id')
        ->addColumn('rma_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'nullable'  => false,
        ), 'customer ID')
        ->addColumn('item_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'nullable'  => false,
        ), 'item ID')
        ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'nullable'  => false,
        ), 'Product ID')
        ->addColumn('product_name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
            'nullable'  => false,
        ), 'Product Name')
        ->addColumn('qty', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'nullable'  => false,
        ), 'qty')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
            'nullable'  => false,
        ));
    $installer->getConnection()->createTable($table);
}

$exists = (boolean) Mage::getSingleton('core/resource')
    ->getConnection('core_write')
    ->showTableStatus(trim($installer->getTable('cminds_rma/rma_status'),'`'));

if(!$exists) {
    $table = $installer->getConnection()
        ->newTable($installer->getTable('cminds_rma/rma_status'))
        ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
        ), 'Id')
        ->addColumn('name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
            'nullable' => false,
        ), 'Name')
        ->addColumn('sort_order', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'nullable' => false,
        ), 'Sort Order')
        ->addColumn('is_closing', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'nullable' => false,
        ), 'Closing')
        ->addColumn('is_system', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'nullable' => false,
            'default' => 0,
        ), 'is_system')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
            'nullable' => false,
        ));
    $installer->getConnection()->createTable($table);
}

$exists = (boolean) Mage::getSingleton('core/resource')
    ->getConnection('core_write')
    ->showTableStatus(trim($installer->getTable('cminds_rma/rma_reason'),'`'));

if(!$exists) {
    $table = $installer->getConnection()
        ->newTable($installer->getTable('cminds_rma/rma_reason'))
        ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
        ), 'Id')
        ->addColumn('name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
            'nullable' => false,
        ), 'Name')
        ->addColumn('sort_order', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'nullable' => false,
        ), 'Sort Order')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
            'nullable' => false,
        ));
    $installer->getConnection()->createTable($table);
}
$exists = (boolean) Mage::getSingleton('core/resource')
    ->getConnection('core_write')
    ->showTableStatus(trim($installer->getTable('cminds_rma/rma_type'),'`'));

if(!$exists) {
    $table = $installer->getConnection()
        ->newTable($installer->getTable('cminds_rma/rma_type'))
        ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
        ), 'Id')
        ->addColumn('name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
            'nullable' => false,
        ), 'Reason Name')
        ->addColumn('sort_order', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'nullable' => false,
        ), 'Sort Order')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
            'nullable' => false,
        ));
    $installer->getConnection()->createTable($table);
}

$exists = (boolean) Mage::getSingleton('core/resource')
    ->getConnection('core_write')
    ->showTableStatus(trim($installer->getTable('cminds_rma/rma_comment'),'`'));

if(!$exists) {
    $table = $installer->getConnection()
        ->newTable($installer->getTable('cminds_rma/rma_comment'))
        ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
        ), 'Id')
        ->addColumn('rma_id', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
            'nullable' => false,
        ), 'Reason Name')
        ->addColumn('comment_body', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
            'nullable' => false,
        ), 'Customer Body')
        ->addColumn('is_customer_notified', Varien_Db_Ddl_Table::TYPE_TINYINT, null, array(
            'nullable' => false,
        ), 'Sort Order')
        ->addColumn('old_status_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'nullable' => false,
        ), 'Old New ID')
        ->addColumn('status_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'nullable' => false,
        ), 'New ID')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
            'nullable' => false,
        ));
    $installer->getConnection()->createTable($table);
}
if (!$installer->getConnection()->fetchOne("SELECT * FROM {$this->getTable('cminds_rma/rma_status')} where entity_id = ".Cminds_Rma_Model_Rma::DEFAULT_OPEN_ID)) {
    $installer->run("
        INSERT INTO {$this->getTable('cminds_rma/rma_status')} (entity_id, name, sort_order, is_closing, is_system, created_at)
VALUES
	(".Cminds_Rma_Model_Rma::DEFAULT_OPEN_ID.", 'Open', 0, 0, 1, '0000-00-00 00:00:00');

    ");
}
if (!$installer->getConnection()->fetchOne("SELECT * FROM {$this->getTable('cminds_rma/rma_status')} where entity_id = ".Cminds_Rma_Model_Rma::DEFAULT_CLOSED_ID)) {
    $installer->run("
        INSERT INTO {$this->getTable('cminds_rma/rma_status')} (entity_id, name, sort_order, is_closing, is_system, created_at)
VALUES
	(".Cminds_Rma_Model_Rma::DEFAULT_CLOSED_ID.", 'Closed', 9999999, 1, 1, '0000-00-00 00:00:00');
    ");
}
if (!$installer->getConnection()->fetchOne("SELECT * FROM {$this->getTable('cminds_rma/rma_status')} where entity_id = ".Cminds_Rma_Model_Rma::DEFAULT_CANCELED_ID)) {
    $installer->run("
        INSERT INTO {$this->getTable('cminds_rma/rma_status')} (entity_id, name, sort_order, is_closing, is_system, created_at)
VALUES
	(".Cminds_Rma_Model_Rma::DEFAULT_CANCELED_ID.", 'Canceled', 9999999, 1, 1, '0000-00-00 00:00:00');
    ");
}


$installer->endSetup();