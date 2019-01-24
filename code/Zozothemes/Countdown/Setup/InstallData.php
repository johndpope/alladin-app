<?php
/** 
  * Zozothemes.
  * 
  * NOTICE OF LICENSE
  * 
  * This source file is subject to the Zozothemes.com license that is
  * available through the world-wide-web at this URL:
  * http://www.zozothemes.com/license-agreement.html
  * 
  * DISCLAIMER
  * 
  * Do not edit or add to this file if you wish to upgrade this extension to newer
  * version in the future.
  * 
  * @category   Zozothemes
  * @package    Zozothemes_Countdown
  * @copyright  Copyright (c) 2014 Zozothemes (http://www.zozothemes.com/)
  * @license    http://www.zozothemes.com/LICENSE-1.0.html
  */
namespace Zozothemes\Countdown\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{

/**
* EAV setup factory
*
* @var EavSetupFactory
*/

private $eavSetupFactory;

 

/**
* Init
*
* @param EavSetupFactory $eavSetupFactory
*/

public function __construct(EavSetupFactory $eavSetupFactory)

{

    $this->eavSetupFactory = $eavSetupFactory;

}

 
public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
{

    /** @var EavSetup $eavSetup */

    $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

 

    /**
    * Add attributes to the eav/attribute
    */

    $eavSetup->addAttribute(
        \Magento\Catalog\Model\Product::ENTITY,
        'countdown_enabled',
        [
        'group' => 'General',
        'type' => 'int',
        'backend' => '',
        'frontend' => '',
        'label' => 'Show Countdown',
        'input' => 'boolean',
        'class' => '',
        'source' => '',
        'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_GLOBAL,
        'visible' => true,
        'required' => false,
        'user_defined' => true,
        'default' => '',
        'searchable' => false,
        'filterable' => false,
        'comparable' => false,
        'visible_on_front' => false,
        'used_in_product_listing' => true,
        'unique' => false,
        'apply_to' => 'simple,configurable,virtual,bundle,downloadable'
        ]
        ); 
    }

}