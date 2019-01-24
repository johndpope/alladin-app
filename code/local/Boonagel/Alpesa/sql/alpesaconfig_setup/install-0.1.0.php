<?php

$installer = $this;

//start the installer setup
$installer->startSetup();




//create table alpesa_config::contains static configuration for the alpesa module
$table = $installer->getConnection()
        ->newTable($installer->getTable('alpesa/alpesaconfig'))
        ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
            'identity' => true,
                ), 'CONFIG ID')
        ->addColumn('allow_payment', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'Allow user to pay using their points')
        ->addColumn('config_priority', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'CONFIG PRIORITY')
        ->addColumn('rule_type', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
            'nullable' => true,
                ), 'RULE TYPE')
        ->addColumn('percentage_reedemable', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '50',
                ), 'REDEEMABLE PERCENTAGE')
        ->addColumn('currency_point', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
            'nullable' => false,
            'default' => '25,1'
                ), 'CURRENCY TO POINT CONVERSION')
        ->addColumn('point_currency', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
            'nullable' => false,
            'default' => '1,1.25'
                ), 'POINT TO CURRENCY CONVERSION')
        ->addColumn('newsletter_points', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
            'nullable' => false,
            'default' => 'no,0'
                ), 'NEWSLETTER SIGN UP POINTS')
        ->addColumn('signup_points', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
            'nullable' => false,
            'default' => 'yes,2500'
                ), 'SIGN UP POINTS')
        ->addColumn('login_points', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
            'nullable' => false,
            'default' => 'yes,50'
                ), 'LOGIN POINTS')
        ->addColumn('login_interval', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
            'nullable' => false,
            'default' => '24,h'
                ), 'LOGIN INTERVAL')
        ->addColumn('referral_points', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
            'nullable' => false,
            'default' => 'yes,1000'
                ), 'REFERRAL POINTS')
        ->addColumn('minimum_points', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'MINIMUM POINTS')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'CREATED AT')
        ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'UPDATED AT')
        ->setComment('Alpesa general static configurations');
$installer->getConnection()->createTable($table);



//create table alpesa_card::contains rules that define the card rules 
$table = $installer->getConnection()
        ->newTable($installer->getTable('alpesa/alpesacard'))
        ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
            'identity' => true,
                ), 'CARD ID')
        ->addColumn('card_name', Varien_Db_Ddl_Table::TYPE_TEXT, 64, array(
            'nullable' => true,
                ), 'CARD NAME')
        ->addColumn('card_min_max_points', Varien_Db_Ddl_Table::TYPE_TEXT, 64, array(
            'nullable' => false,
            'default' => '0,0',
                ), 'CARD MINIMUM AND MAXIMUM POINTS')
        ->addColumn('card_color', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
            'nullable' => true,
                ), 'CARD COLOR')
        ->addColumn('card_voucher_amount', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,2', array(
            'nullable' => false,
            'default' => '0'
                ), 'CARD VOUCHER AMOUNT')
        ->addColumn('card_discount', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'CARD DISCOUNT')
        ->addColumn('card_gift_date', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
            'nullable' => false,
            'default' => 'annualy,3,2,1',
                ), 'CARD GIFT DATE')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'CREATED AT')
        ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'UPDATED AT')
        ->setComment('Alpesa card configurations');
$installer->getConnection()->createTable($table);



//create table alpesa_condition::contains conditional rules that govern how points are awarded
$table = $installer->getConnection()
        ->newTable($installer->getTable('alpesa/alpesacondition'))
        ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
            'identity' => true,
                ), 'CONDITION ID')
        ->addColumn('config_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'CONFIGURATION ID')
        ->addColumn('condition_type', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
            'nullable' => false,
            'default' => 'visits',
                ), 'CARD GIFT DATE')
        ->addColumn('condition_scope', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
            'nullable' => true,
                ), 'CONDITION SCOPE')
        ->addColumn('condition_operator', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
            'nullable' => true,
                ), 'CONDITION OPERATOR')
        ->addColumn('points_target', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'POINTS TARGET')
        ->addColumn('points_reward', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'POINTS REWARD')
        ->addColumn('visits', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'CONDITION VISITS')
        ->addColumn('per_visit_spending', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,2', array(
            'nullable' => false,
            'default' => '0'
                ), 'PER VISIT SPENDING')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'CREATED AT')
        ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'UPDATED AT')
        ->setComment('Alpesa conditions table');
$installer->getConnection()->createTable($table);



//create table alpesa_wallet::contains the point and amount in the wallet
$table = $installer->getConnection()
        ->newTable($installer->getTable('alpesa/alpesawallet'))
        ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
            'identity' => true,
                ), 'WALLET ID')
        ->addColumn('user_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'USER ID')
        ->addColumn('available_points', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'AVAILABLE POINTS')
        ->addColumn('actual_points', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'ACTUAL POINTS')
        ->addColumn('wallet', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,2', array(
            'nullable' => false,
            'default' => '0'
                ), 'WALLET')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'CREATED AT')
        ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'UPDATED AT')
        ->setComment('Alpesa wallet configurations and user data');
$installer->getConnection()->createTable($table);



//create table alpesa_redeem::contains logs on the amount and points used by the alladin wallet through reedem concept.
$table = $installer->getConnection()
        ->newTable($installer->getTable('alpesa/alpesaredeem'))
        ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
            'identity' => true,
                ), 'REDEEM ID')
        ->addColumn('user_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'USER ID')
        ->addColumn('points', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'POINTS')
        ->addColumn('status', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'STATUS')
        ->addColumn('amount', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,2', array(
            'nullable' => false,
            'default' => '0'
                ), 'AMOUNT')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'CREATED AT')
        ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'UPDATED AT')
        ->setComment('Alpesa redeem logs');
$installer->getConnection()->createTable($table);



//create table alpesa_points::contains all the points and there equivalent amounts,
//statuses whether is actual or available balance
$table = $installer->getConnection()
        ->newTable($installer->getTable('alpesa/alpesapoints'))
        ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
            'identity' => true,
                ), 'POINTS ID')
        ->addColumn('user_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'USER ID')
        ->addColumn('amount', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,2', array(
            'nullable' => false,
            'default' => '0'
                ), 'AMOUNT')
        ->addColumn('status', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'STATUS')
        ->addColumn('points', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'POINTS')
        ->addColumn('order_number', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
            'nullable' => true,
                ), 'ORDER NUMBER')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'CREATED AT')
        ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'UPDATED AT')
        ->setComment('Alpesa points log');
$installer->getConnection()->createTable($table);



//create table alpesa_user::logs spending-session and logging-session
$table = $installer->getConnection()
        ->newTable($installer->getTable('alpesa/alpesauser'))
        ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
            'identity' => true,
                ), 'ID')
        ->addColumn('log_type', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
            'nullable' => false,
            'default' => 'spending-session'
                ), 'LOG TYPE')
        ->addColumn('user_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'LOGGED USER ID')
        ->addColumn('complete_transaction', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'TRANSACTION COMPLETE')
        ->addColumn('session_amount', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,2', array(
            'nullable' => false,
            'default' => '0'
                ), 'SESSION AMOUNT')
        ->addColumn('order_number', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'ORDER NUMBER')
        ->addColumn('current_login', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'CURRENT LOGIN')
        ->addColumn('next_login', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'NEXT LOGIN')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'CREATED AT')
        ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'UPDATED AT')
        ->setComment('Alpesa user logs');
$installer->getConnection()->createTable($table);



//create table target_achieved_logs::logs all the targets that a specifc user has achieved.
$table = $installer->getConnection()
        ->newTable($installer->getTable('alpesa/alpesatarget'))
        ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
            'identity' => true,
                ), 'ID')
        ->addColumn('user_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'LOGGED USER ID')
        ->addColumn('point_target', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'POINTS TARGET LOGGED')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'CREATED AT')
        ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'UPDATED AT')
        ->setComment('Alpesa user achieved target logs');
$installer->getConnection()->createTable($table);



//create table discounted_logs::logs all the products on discount as set by the administrator.
$table = $installer->getConnection()
        ->newTable($installer->getTable('alpesa/alpesadiscounted'))
        ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
            'identity' => true,
                ), 'ID')
        ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'PRODUCT ID')
        ->addColumn('card_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'CARD ID WHICH THIS PRODUCT BELONGS TO')
        ->addColumn('discount_percentage', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'DISCOUNT PERCENTAGE')
        ->addColumn('actual_amount', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,2', array(
            'nullable' => false,
            'default' => '0'
                ), 'ACTUAL AMOUNT')
        ->addColumn('discounted_amount', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,2', array(
            'nullable' => false,
            'default' => '0'
                ), 'DISCOUNTED AMOUNT')
        ->addColumn('prod_disc_start_date', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'DISCOUNT START DATE')
        ->addColumn('prod_disc_end_date', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'DISCOUNT END DATE')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'CREATED AT')
        ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'UPDATED AT')
        ->setComment('Alpesa discounted products log');
$installer->getConnection()->createTable($table);



//create table voucher_used_logs::logs of all the vouchers used and to which products they were used.
$table = $installer->getConnection()
        ->newTable($installer->getTable('alpesa/alpesavoucher'))
        ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
            'identity' => true,
                ), 'ID')
        ->addColumn('user_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'USER ID')
        ->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'ORDER ID PAID FOR')
        ->addColumn('validated', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'VALIDATED TRANSACTIONS')
        ->addColumn('voucher_amount', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,2', array(
            'nullable' => false,
            'default' => '0'
                ), 'VOCHER AMOUNT')
        ->addColumn('order_amount', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,2', array(
            'nullable' => false,
            'default' => '0'
                ), 'VOUCHER PRODUCT AMOUNT')
        ->addColumn('voucher_used', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,2', array(
            'nullable' => false,
            'default' => '0'
                ), 'VOUCHER USED AMOUNT')
        ->addColumn('voucher_date', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'VOUCHER DATE USED')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'CREATED AT')
        ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'UPDATED AT')
        ->setComment('Alpesa used voucher logs');
$installer->getConnection()->createTable($table);



//create table alpesa_used_amt_invoice::logs all the orders and the amount used to pay for each.
$table = $installer->getConnection()
        ->newTable($installer->getTable('alpesa/alpesainvoice'))
        ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
            'identity' => true,
                ), 'ID')
        ->addColumn('user_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'USER ID')
        ->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'ID OF PAID FOR INVOICE')
        ->addColumn('validated', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'VALIDATED TRANSACTIONS')
        ->addColumn('used_amount', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,2', array(
            'nullable' => false,
            'default' => '0'
                ), 'USED AMOUNT')
        ->addColumn('used_date', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'DATE AMOUNT USED')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'CREATED AT')
        ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'UPDATED AT')
        ->setComment('Alpesa used amount on a specific order');
$installer->getConnection()->createTable($table);



//create table to store the referral codes
$table = $installer->getConnection()
        ->newTable($installer->getTable('alpesa/alpesarefcodes'))
        ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
            'identity' => true,
                ), 'ID')
        ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'CUSTOMER ID')
        ->addColumn('code', Varien_Db_Ddl_Table::TYPE_TEXT, 64, array(
            'nullable' => false,
            'default' => 'abcd0987',
                ), 'REFERRAL CODE')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'CREATED AT')
        ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'UPDATED AT')
        ->setComment('Alpesa referee codes');
$installer->getConnection()->createTable($table);



//create table to store the referral points
$table = $installer->getConnection()
        ->newTable($installer->getTable('alpesa/alpesarefpoints'))
        ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
            'identity' => true,
                ), 'ID')
        ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'CUSTOMER ID')
        ->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'ORDER ID')
        ->addColumn('actual', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'REFERRAL POINT AS ACTUAL OR AVAILABLE')
        ->addColumn('points', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'POINTS')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'CREATED AT')
        ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'UPDATED AT')
        ->setComment('Alpesa referee points');
$installer->getConnection()->createTable($table);




//create table to store the referred and registered customers
$table = $installer->getConnection()
        ->newTable($installer->getTable('alpesa/alpesarefcustomer'))
        ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
            'identity' => true,
                ), 'ID')
        ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'CUSTOMER ID')
        ->addColumn('referee_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'REFEREE ID')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'CREATED AT')
        ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'UPDATED AT')
        ->setComment('Alpesa refered customers and their refeerees');
$installer->getConnection()->createTable($table);


//end the installer setup
$installer->endSetup();


//CBA payment pending
$status = Mage::getModel('sales/order_status');
$status->setStatus('alpesa_payment_processing')->setLabel('ALPESA PAYMENT PROCESSING');
$status->save();
$status->assignState(Mage_Sales_Model_Order::STATE_PROCESSING);

//CBA payment COMPLETE
$status = Mage::getModel('sales/order_status');
$status->setStatus('alpesa_payment_complete')->setLabel('ALPESA PAYMENT COMPLETE');
$status->save();
$status->assignState(Mage_Sales_Model_Order::STATE_PROCESSING);
