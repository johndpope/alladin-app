<?php
/* @var $installer Mage_Customer_Model_Entity_Setup */
$installer = $this;

$installer->startSetup();


$installer->getConnection()
    ->addColumn($installer->getTable('suppliersubscriptions/plans'),
        'product_id',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'nullable' => false,
            'unsigned' => true,
            'comment'  => 'Plan product id'
        )
    );

$installer->endSetup();