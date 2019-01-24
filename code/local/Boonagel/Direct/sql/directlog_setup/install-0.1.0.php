<?php

$installer = $this;

//start the installer setup
$installer->startSetup();



//create table direct_log::stores all transactional logs
$table = $installer->getConnection()
        ->newTable($installer->getTable('direct/directlog'))
        ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
            'identity' => true,
                ), 'LOG ID')
        ->addColumn('status', Varien_Db_Ddl_Table::TYPE_TEXT, 64, array(
            'nullable' => FALSE,
            'default' => 'unverified'
                ), 'STATUS ie unverified OR complete')
        ->addColumn('transaction_token', Varien_Db_Ddl_Table::TYPE_TEXT, 256, array(
            'nullable' => true
                ), 'TRANSACTION TOKEN')
        ->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'ORDER ID')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'CREATED AT')
        ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'UPDATED AT')
        ->setComment('DirectPayOnline Logs');
$installer->getConnection()->createTable($table);


//end the installer setup
$installer->endSetup();

//Direct payment complete
$status = Mage::getModel('sales/order_status');
$status->setStatus('directpayonline_payment_complete')->setLabel('DIRECTPAYONLINE COMPLETE');
$status->save();
$status->assignState(Mage_Sales_Model_Order::STATE_PROCESSING);

//Direct payment complete
$status = Mage::getModel('sales/order_status');
$status->setStatus('directpayonline_payment_unverified')->setLabel('DIRECTPAYONLINE UNVERIFIED');
$status->save();
$status->assignState(Mage_Sales_Model_Order::STATE_PROCESSING);
