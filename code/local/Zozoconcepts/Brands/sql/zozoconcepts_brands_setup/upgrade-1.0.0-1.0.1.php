<?php
$collection = Mage::getModel('zozoconcepts_brands/brand')->getCollection();
$count = count($collection);
if($count==0){
$installer = $this;
$installer->startSetup();
$installer->run("
INSERT INTO {$this->getTable('zozoconcepts_brands/brand')} (`title`, `brand_icon`, `brand_image`, `brand_descriptions`, `featured_brands`, `status`, `url_key`, `created_at`) VALUES
('Brand 1', '/b/r/brand.png', '/b/r/brand.png', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat', '1', '1', 'brand_1', '2016-01-01 00:00:00'), ('Brand 2', '/b/r/brand.png', '/b/r/brand.png', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat', '1', '1', 'brand_2', '2016-01-01 00:00:00'), ('Brand 3', '/b/r/brand.png', '/b/r/brand.png', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat', '1', '1', 'brand_3', '2016-01-01 00:00:00'), ('Brand 4', '/b/r/brand.png', '/b/r/brand.png', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat', '1', '1', 'brand_4', '2016-01-01 00:00:00');

INSERT INTO {$this->getTable('zozoconcepts_brands/brand_store')} (`brand_id`, `store_id`) VALUES ((SELECT entity_id FROM {$this->getTable('zozoconcepts_brands/brand')} WHERE url_key='brand_1'), '0'),((SELECT entity_id FROM {$this->getTable('zozoconcepts_brands/brand')} WHERE url_key='brand_2'), '0'), ((SELECT entity_id FROM {$this->getTable('zozoconcepts_brands/brand')} WHERE url_key='brand_3'), '0'), ((SELECT entity_id FROM {$this->getTable('zozoconcepts_brands/brand')} WHERE url_key='brand_4'), '0');
");
$installer->endSetup();}