<?php
$installer = $this;
$installer->startSetup();
$installer->run("-- DROP TABLE IF EXISTS {$this->getTable('sms_register_otp')};
CREATE TABLE {$this->getTable('sms_register_otp')} (
	  `id` int(11) unsigned NOT NULL auto_increment,
	  `random_code` varchar(255) NOT NULL default '',
	  `created_time` datetime NULL,
	  `customer_id` int(11) NOT NULL default '0',
	  `is_verify` int(11) NOT NULL default '0',
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	");

$installer->run("-- DROP TABLE IF EXISTS {$this->getTable('sms_report')};
CREATE TABLE {$this->getTable('sms_report')} (
	  `id` int(11) unsigned NOT NULL auto_increment,
	  `jobno` varchar(255) NOT NULL default '',
	  `message_sent_time` datetime NULL,
	  `message_text` varchar(255) NOT NULL default '',
	  `message_count` int(11) NOT NULL default '0',
	  `recipient_name` varchar(255) NOT NULL default '',
	  `recipient_number` varchar(255) NOT NULL default '',  
	  `message_type` varchar(255) NOT NULL default '',	  	  
	  `message_status` int(11) NOT NULL default '0',	  	  
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	");	

 $setup = new Mage_Core_Model_Config();
 $setup->saveConfig('sms/sendtimelyreport/time', date("Y-m-d"), 'default', 0);

	$installer->endSetup();
	

