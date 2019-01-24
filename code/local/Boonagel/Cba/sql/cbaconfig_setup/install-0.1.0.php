<?php

$installer = $this;

//start the installer setup
$installer->startSetup();




//create table cba_config::contains static configuration for the cba module
$table = $installer->getConnection()
        ->newTable($installer->getTable('cba/cbaconfig'))
        ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
            'identity' => true,
                ), 'CONFIG ID')
        ->addColumn('username', Varien_Db_Ddl_Table::TYPE_TEXT, 64, array(
            'nullable' => false,
            'default' => 'borntolive'
                ), 'SERVICE USERNAME')
        ->addColumn('password', Varien_Db_Ddl_Table::TYPE_TEXT, 64, array(
            'nullable' => false,
            'default' => 'independenceday'
                ), 'SERVICE PASSWORD')
        ->addColumn('secret', Varien_Db_Ddl_Table::TYPE_TEXT, 64, array(
            'nullable' => false,
            'default' => '32jfklajdf0809djfsnvxclifasd5732o3hh'
                ), 'SECRET')
        ->addColumn('response_gateway', Varien_Db_Ddl_Table::TYPE_TEXT, 128, array(
            'nullable' => TRUE
                ), 'RESPONSE GATEWAY')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'CREATED AT')
        ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'UPDATED AT')
        ->setComment('Cba general static configurations');
$installer->getConnection()->createTable($table);


//create table cba_log::stores all transactional logs
$table = $installer->getConnection()
        ->newTable($installer->getTable('cba/cbalog'))
        ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
            'identity' => true,
                ), 'LOG ID')
        ->addColumn('hash_value', Varien_Db_Ddl_Table::TYPE_TEXT, 64, array(
            'nullable' => true
                ), 'HASH VALUE FROM RESPONSE')
        ->addColumn('trans_type', Varien_Db_Ddl_Table::TYPE_TEXT, 64, array(
            'nullable' => true
                ), 'TRANSACTION TYPE')
        ->addColumn('trans_id', Varien_Db_Ddl_Table::TYPE_TEXT, 64, array(
            'nullable' => true
                ), 'TRANSACTION ID')
        ->addColumn('trans_time', Varien_Db_Ddl_Table::TYPE_TEXT, 64, array(
            'nullable' => true
                ), 'TRANSACTION TIME')
        ->addColumn('trans_amount', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,2', array(
            'nullable' => true,
            'default' => '0'
                ), 'TRANSACTION AMOUNT')
        ->addColumn('account_nr', Varien_Db_Ddl_Table::TYPE_TEXT, 64, array(
            'nullable' => true
                ), 'ACCOUNT NUMBER')
        ->addColumn('narrative', Varien_Db_Ddl_Table::TYPE_TEXT, 64, array(
            'nullable' => true
                ), 'NARRATIVE')
        ->addColumn('phone_nr', Varien_Db_Ddl_Table::TYPE_TEXT, 64, array(
            'nullable' => true
                ), 'PHONE NUMBER')
        ->addColumn('customer_name', Varien_Db_Ddl_Table::TYPE_TEXT, 64, array(
            'nullable' => true
                ), 'CUSTOMER NAME')
        ->addColumn('status', Varien_Db_Ddl_Table::TYPE_TEXT, 64, array(
            'nullable' => true
                ), 'STATUS')
        ->addColumn('comments', Varien_Db_Ddl_Table::TYPE_TEXT, 256, array(
            'nullable' => true
                ), 'STATUS')
        ->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'ORDER ID')
        ->addColumn('response', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'RESPONSE TO CBA')
        ->addColumn('erronous', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'ERROR ORDER ID')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'CREATED AT')
        ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'UPDATED AT')
        ->setComment('Cba payment logs');
$installer->getConnection()->createTable($table);


//end the installer setup
$installer->endSetup();

//CBA payment pending
$status = Mage::getModel('sales/order_status');
$status->setStatus('cba_payment_pending')->setLabel('MPESA CBA Payment Pending');
$status->save();
$status->assignState(Mage_Sales_Model_Order::STATE_PROCESSING);

//CBA payment COMPLETE
$status = Mage::getModel('sales/order_status');
$status->setStatus('cba_payment_complete')->setLabel('MPESA CBA Payment Complete');
$status->save();
$status->assignState(Mage_Sales_Model_Order::STATE_PROCESSING);

