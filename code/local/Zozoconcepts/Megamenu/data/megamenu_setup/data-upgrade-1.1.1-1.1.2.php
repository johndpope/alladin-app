<?php
$installer = $this;
$installer->removeAttribute('catalog_category', 'umm_dd_menu_icon');
//Add attributes

$installer->addAttribute('catalog_category', 'cat_icon', array(
    'group'                    => 'Menu',
	'note'					 => 'Upload your category icon here to show that above the top menu label. <i>note: do not use this for sub categories</i><br><b>Supported format .gif, .jpg, .png, .jpeg</b>',
    'label'                    => 'Category Icon',
    'input'                    => 'image',
    'type'                     => 'varchar',
    'backend'                  => 'megamenu/category_attribute_backend_file',
    'global'                   => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible'                  => true,
    'required'                 => false,
    'user_defined'             => true,
    'order'                    => 20
));




Mage::log("Some example text", null, "MyFile.log");



$installer->endSetup();
