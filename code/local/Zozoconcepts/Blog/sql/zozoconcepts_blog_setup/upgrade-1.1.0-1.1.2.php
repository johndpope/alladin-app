<?php
$collection = Mage::getModel('zozoconcepts_blog/blog')->getCollection();
$count = count($collection);
if($count==0){
$installer = $this;
$installer->startSetup();
$installer->run("
INSERT INTO {$this->getTable('zozoconcepts_blog/blog')} (`title`, `excerpt`, `full_description`, `featured_image`, `status`, `url_key`, `created_at`) VALUES
('Hello World', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat', '/b/l/blog.png', '1', 'hello-world', '2016-01-01 00:00:00'), ('Another Example Post', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat', '/b/l/blog.png', '1', 'another-example-post', '2016-01-01 00:00:00');

INSERT INTO {$this->getTable('zozoconcepts_blog/blog_store')} (`blog_id`, `store_id`) VALUES ((SELECT entity_id FROM {$this->getTable('zozoconcepts_blog/blog')} WHERE title='Hello World'), '0'),((SELECT entity_id FROM {$this->getTable('zozoconcepts_blog/blog')} WHERE title='Another Example Post'), '0');
");
$installer->endSetup();}