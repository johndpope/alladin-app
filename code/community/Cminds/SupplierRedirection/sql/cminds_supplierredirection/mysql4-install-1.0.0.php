<?php
$installer = $this;
$installer->startSetup();

$db = Mage::getSingleton('core/resource')->getConnection('core_write');
$table_prefix = Mage::getConfig()->getTablePrefix();

$entity = $this->getEntityTypeId('customer');

$this->addAttribute($entity, 'domain_url', array(
    'type' => 'text',
    'label' => __('Domain URL'),
    'input' => 'text',
    'visible' => TRUE,
    'required' => FALSE,
    'default_value' => 1,
    'adminhtml_only' => '1'
));

$installer->endSetup();
