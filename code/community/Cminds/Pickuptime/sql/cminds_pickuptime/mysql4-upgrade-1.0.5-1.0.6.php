<?php
$installer = $this;
$installer->startSetup();

$table = $installer->getTable('cminds_pickuptime/vendor_pickup_time');

$installer->run("ALTER TABLE `{$table}`
    CHANGE COLUMN `monday_time_start` `monday_time_start` TIME NOT NULL DEFAULT '00:00:00' COMMENT 'Monday Time Start'
");

$installer->run("ALTER TABLE `{$table}`
    CHANGE COLUMN `monday_time_end` `monday_time_end` TIME NOT NULL DEFAULT '00:00:00' COMMENT 'Monday Time End'
");

$installer->run("ALTER TABLE `{$table}`
    CHANGE COLUMN `tuesday_time_start` `tuesday_time_start` TIME NOT NULL DEFAULT '00:00:00' COMMENT 'Tuesday Time Start'
");

$installer->run("ALTER TABLE `{$table}`
    CHANGE COLUMN `tuesday_time_end` `tuesday_time_end` TIME NOT NULL DEFAULT '00:00:00' COMMENT 'Tuesday Time End'
");

$installer->run("ALTER TABLE `{$table}`
    CHANGE COLUMN `wednesday_time_start` `wednesday_time_start` TIME NOT NULL DEFAULT '00:00:00' COMMENT 'Wednesday Time Start'
");

$installer->run("ALTER TABLE `{$table}`
    CHANGE COLUMN `wednesday_time_end` `wednesday_time_end` TIME NOT NULL DEFAULT '00:00:00' COMMENT 'Wednesday Time End'
");

$installer->run("ALTER TABLE `{$table}`
    CHANGE COLUMN `thursday_time_start` `thursday_time_start` TIME NOT NULL DEFAULT '00:00:00' COMMENT 'Thursday Time Start'
");

$installer->run("ALTER TABLE `{$table}`
    CHANGE COLUMN `thursday_time_end` `thursday_time_end` TIME NOT NULL DEFAULT '00:00:00' COMMENT 'Thursday Time End'
");

$installer->run("ALTER TABLE `{$table}`
    CHANGE COLUMN `friday_time_start` `friday_time_start` TIME NOT NULL DEFAULT '00:00:00' COMMENT 'Fridayy Time Start'
");

$installer->run("ALTER TABLE `{$table}`
    CHANGE COLUMN `friday_time_end` `friday_time_end` TIME NOT NULL DEFAULT '00:00:00' COMMENT 'Friday Time End'
");

$installer->run("ALTER TABLE `{$table}`
    CHANGE COLUMN `saturday_time_start` `saturday_time_start` TIME NOT NULL DEFAULT '00:00:00' COMMENT 'Saturday Time Start'
");

$installer->run("ALTER TABLE `{$table}`
    CHANGE COLUMN `saturday_time_end` `saturday_time_end` TIME NOT NULL DEFAULT '00:00:00' COMMENT 'Saturday Time End'
");

$installer->run("ALTER TABLE `{$table}`
    CHANGE COLUMN `sunday_time_start` `sunday_time_start` TIME NOT NULL DEFAULT '00:00:00' COMMENT 'Sunday Time Start'
");

$installer->run("ALTER TABLE `{$table}`
    CHANGE COLUMN `sunday_time_end` `sunday_time_end` TIME NOT NULL DEFAULT '00:00:00' COMMENT 'Sunday Time End'
");

$table = $installer->getTable('cminds_pickuptime/vendor_pickup_time_excluded_days');

$installer->run("ALTER TABLE `{$table}`
    CHANGE COLUMN `start_date` `start_date` TIME NOT NULL DEFAULT '00:00:00' COMMENT 'Start Date'
");

$installer->run("ALTER TABLE `{$table}`
    CHANGE COLUMN `end_date` `end_date` TIME NOT NULL DEFAULT '00:00:00' COMMENT 'End Date'
");

$installer->endSetup();
