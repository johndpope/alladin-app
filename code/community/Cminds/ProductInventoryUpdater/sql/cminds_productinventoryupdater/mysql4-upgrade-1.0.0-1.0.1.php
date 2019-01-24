<?php
$installer = $this;
$installer->startSetup();

$entity = $this->getEntityTypeId('customer');

$this->addAttribute($entity, 'updater_csv_attribute', array(
    'type' => 'text',
    'source' => 'eav/entity_attribute_source_table',
    'label' => 'Matching Csv Attribute',
    'input' => 'text',
    'visible' => TRUE,
    'required' => FALSE,
    'default_value' => '',
    'adminhtml_only' => '1'
));

Mage::getSingleton('eav/config')
    ->getAttribute('customer', 'updater_csv_attribute')
    ->setData('used_in_forms', array('adminhtml_customer','customer_account_create','customer_account_edit'))
    ->save();

$installer->endSetup();
