<?php
$installer = $this;
$installer->startSetup();

$table_prefix = Mage::getConfig()->getTablePrefix();
$entity = $this->getEntityTypeId('customer');
$this->addAttribute($entity, 'updater_csv_link', array(
    'type' => 'text',
    'label' => 'Updater Csv Link',
    'input' => 'text',
    'visible' => TRUE,
    'required' => FALSE,
    'default_value' => '',
    'adminhtml_only' => '1'
));


$this->addAttribute($entity, 'updater_csv_column', array(
    'type' => 'text',
    'label' => 'Matching Csv Column',
    'input' => 'text',
    'visible' => TRUE,
    'required' => FALSE,
    'default_value' => '',
    'adminhtml_only' => '1'
));

$this->addAttribute($entity, 'updater_qty_column', array(
    'type' => 'text',
    'label' => 'Matching Qty Column',
    'input' => 'text',
    'visible' => TRUE,
    'required' => FALSE,
    'default_value' => '',
    'adminhtml_only' => '1'
));

$this->addAttribute($entity, 'updater_csv_action', array(
    'type' => 'int',
    'source' => 'productinventoryupdater/source_action',
    'label' => 'In case of missing product in the Feed',
    'input' => 'select',
    'visible' => TRUE,
    'required' => FALSE,
    'default_value' => '',
    'adminhtml_only' => '1'
));

Mage::getSingleton('eav/config')
    ->getAttribute('customer', 'updater_csv_link')
    ->setData('used_in_forms', array('adminhtml_customer','customer_account_create','customer_account_edit'))
    ->save();

Mage::getSingleton('eav/config')
    ->getAttribute('customer', 'updater_csv_column')
    ->setData('used_in_forms', array('adminhtml_customer','customer_account_create','customer_account_edit'))
    ->save();

Mage::getSingleton('eav/config')
    ->getAttribute('customer', 'updater_qty_column')
    ->setData('used_in_forms', array('adminhtml_customer','customer_account_create','customer_account_edit'))
    ->save();

Mage::getSingleton('eav/config')
    ->getAttribute('customer', 'updater_csv_action')
    ->setData('used_in_forms', array('adminhtml_customer','customer_account_create','customer_account_edit'))
    ->save();


$installer->endSetup();