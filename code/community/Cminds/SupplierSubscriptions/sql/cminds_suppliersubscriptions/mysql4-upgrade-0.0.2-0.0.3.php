<?php
/* @var $installer Mage_Customer_Model_Entity_Setup */
$installer = $this;

$installer->startSetup();

$installer->addAttribute("customer", "plan_from_date",  array(
    "type"     => "datetime",
    "backend"  => "eav/entity_attribute_backend_datetime",
    "label"    => "Supplier Plan from date",
    "input"    => "date",
    "source"   => "",
    "visible"  => true,
    "required" => false,
    "default"  => "",
    "frontend" => "",
    "unique"   => false,
    "note"     => "",
));

$installer->addAttribute("customer", "plan_to_date",  array(
    "type"     => "datetime",
    "backend"  => "eav/entity_attribute_backend_datetime",
    "label"    => "Supplier Plan to date",
    "input"    => "date",
    "source"   => "",
    "visible"  => true,
    "required" => false,
    "default"  => "",
    "frontend" => "",
    "unique"   => false,
    "note"     => "",
));

$installer->addAttribute("customer", "current_plan",  array(
    "type"     => "int",
    "backend"  => "",
    "label"    => "Current plan",
    "input"    => "select",
    "source"   => "suppliersubscriptions/config_plans",
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    "visible"  => true,
    "required" => false,
    "default"  => "",
    "frontend" => "",
    "unique"   => false,
    "note"     => ""

));

$installer->endSetup();