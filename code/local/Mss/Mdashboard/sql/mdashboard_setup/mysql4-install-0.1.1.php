<?php
$installer = $this;
$installer->startSetup();
$sql="CREATE TABLE {$this->getTable('magentomobile_mdashboard')} (
  `id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `order_banner` int(11) NULL default '0',
  `status` smallint(6) NOT NULL default '0',
  `category_id` int(11) NULL,
  `image` varchar(255) NULL,

  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

$installer->run($sql);

$installer->endSetup();
