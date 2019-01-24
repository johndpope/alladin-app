<?php 
/*
 * Make sure the upgrade is not performed on installations without the tables
 * (i.e. unpatched shops).
 */
$adminVersion = Mage::getConfig()->getModuleConfig('Mage_Admin')->version;
if (version_compare($adminVersion, '1.6.1.2', '>=') && Mage::getModel('admin/block') && Mage::getSingleton('core/resource')->getTableName('admin/permission_block')) {

    $blockNames = array(
        'cms/block',
		'catalogcategorysearch/type',
		'megamenu/navigation',
		'newsletter/subscribe',
        'zozoconcepts_brands/brand_list',
		'zozoconcepts_blog/blog_list',
		'zozoconcepts_multideals/multideal_list',
		'zozoconcepts_multideals/multideal_view',
		'zozoconcepts_featuredproductslider/featuredproductslider',
        'zozoconcepts_featuredproductslider/new',
        'zozoconcepts_featuredproductslider/bestseller',
		'zozoconcepts_featuredproductslider/topratted',
        'zozoconcepts_socio/tweets',
		'dailydeal/dailydeal',
		'dailydeal/productdailydeal',
		'dailydeal/sidebar',
		'tag/popular'
    );
    foreach ($blockNames as $blockName) {
        $whitelistBlock = Mage::getModel('admin/block')->load($blockName, 'block_name');
        $whitelistBlock->setData('block_name', $blockName);
        $whitelistBlock->setData('is_allowed', 1);
        $whitelistBlock->save();
    }

    $variableNames = array(
        'design/email/logo_alt',
        'design/email/logo_width',
        'design/email/logo_height',
    );

    foreach ($variableNames as $variableName) {
        $whitelistVar = Mage::getModel('admin/variable')->load($variableName, 'variable_name');
        $whitelistVar->setData('variable_name', $variableName);
        $whitelistVar->setData('is_allowed', 1);
        $whitelistVar->save();
    }
}