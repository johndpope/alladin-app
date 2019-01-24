<?php
$installer = $this;
$installer->startSetup();

$entity = $this->getEntityTypeId('customer');


$this->addAttribute($entity, 'updater_cost_column', array(
    'type' => 'text',
    'label' => 'Matching Cost Column',
    'input' => 'text',
    'visible' => TRUE,
    'required' => FALSE,
    'default_value' => '',
    'adminhtml_only' => '1'
));

Mage::getSingleton('eav/config')
    ->getAttribute('customer', 'updater_cost_column')
    ->setData('used_in_forms', array('adminhtml_customer','customer_account_create','customer_account_edit'))
    ->save();

$installer->endSetup();
