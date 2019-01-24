<?php
$installer = $this;
$installer->startSetup();

$this->getConnection()->addColumn(
    $this->getTable('sales/order_item'),
    'pickup_date',
    'DATETIME'
);

$this->getConnection()->addColumn(
    $this->getTable('sales/quote_item'),
    'pickup_date',
    'DATETIME'
);

$table = $installer->getConnection()
    ->newTable($installer->getTable('cminds_pickuptime/vendor_pickup_time'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Id')
    ->addColumn('vendor_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable'  => false,
    ), 'SUPPLIER ID')
    ->addColumn('days_ahead', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable'  => false,
    ), 'Days Ahead')
    ->addColumn('monday_time_start', Varien_Db_Ddl_Table::TYPE_TIME, null, array(
        'nullable'  => false,
    ), 'Monday Time Start')
    ->addColumn('monday_time_end', Varien_Db_Ddl_Table::TYPE_TIME, null, array(
        'nullable'  => false,
    ), 'Monday Time End')
    ->addColumn('tuesday_time_start', Varien_Db_Ddl_Table::TYPE_TIME, null, array(
        'nullable'  => false,
    ), 'Tuesday Time Start')
    ->addColumn('tuesday_time_end', Varien_Db_Ddl_Table::TYPE_TIME, null, array(
        'nullable'  => false,
    ), 'Tuesday Time End')
    ->addColumn('wednesday_time_start', Varien_Db_Ddl_Table::TYPE_TIME, null, array(
        'nullable'  => false,
    ), 'Wednesday Time Start')
    ->addColumn('wednesday_time_end', Varien_Db_Ddl_Table::TYPE_TIME, null, array(
        'nullable'  => false,
    ), 'Wednesday Time End')
    ->addColumn('thursday_time_start', Varien_Db_Ddl_Table::TYPE_TIME, null, array(
        'nullable'  => false,
    ), 'Thursday Time Start')
    ->addColumn('thursday_time_end', Varien_Db_Ddl_Table::TYPE_TIME, null, array(
        'nullable'  => false,
    ), 'Thursday Time End')
    ->addColumn('friday_time_start', Varien_Db_Ddl_Table::TYPE_TIME, null, array(
        'nullable'  => false,
    ), 'Friday Time Start')
    ->addColumn('friday_time_end', Varien_Db_Ddl_Table::TYPE_TIME, null, array(
        'nullable'  => false,
    ), 'Friday Time End')
    ->addColumn('saturday_time_start', Varien_Db_Ddl_Table::TYPE_TIME, null, array(
        'nullable'  => false,
    ), 'Saturday Time Start')
    ->addColumn('saturday_time_end', Varien_Db_Ddl_Table::TYPE_TIME, null, array(
        'nullable'  => false,
    ), 'Saturday Time End')
    ->addColumn('sunday_time_start', Varien_Db_Ddl_Table::TYPE_TIME, null, array(
        'nullable'  => false,
    ), 'Sunday Time Start')
    ->addColumn('sunday_time_end', Varien_Db_Ddl_Table::TYPE_TIME, null, array(
        'nullable'  => false,
    ), 'Sunday Time End');
$installer->getConnection()->createTable($table);


$table = $installer->getConnection()
    ->newTable($installer->getTable('cminds_pickuptime/vendor_pickup_time_excluded_days'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Id')
    ->addColumn('vendor_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable'  => false,
    ), 'VENDOR ID')
    ->addColumn('date', Varien_Db_Ddl_Table::TYPE_DATE, null, array(
        'nullable'  => false,
    ), 'Excluded date')
    ->addColumn('start_date', Varien_Db_Ddl_Table::TYPE_TIME, null, array(
        'nullable'  => false,
    ), 'Start Date')
    ->addColumn('end_date', Varien_Db_Ddl_Table::TYPE_TIME, null, array(
        'nullable'  => false,
    ), 'End Date');
$installer->getConnection()->createTable($table);

$installer->endSetup();
