<?php

/*
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
 * @package    Zozothemes_
 * @copyright  Copyright (c) 2014 Zozothemes (http://www.zozothemes.com/)
 * @license    http://www.zozothemes.com/LICENSE-1.0.html
 */

/**
 * Description of UpgradeData
 *
 * @author Abileweb
 */

namespace Zozothemes\Megamenu\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Catalog\Setup\CategorySetupFactory;

class UpgradeData implements UpgradeDataInterface
{
    /**
     * Category setup factory
     *
     * @var CategorySetupFactory
     */
    private $categorySetupFactory;
    
    /**
     * Init
     *
     * @param CategorySetupFactory $categorySetupFactory
     */
    public function __construct(CategorySetupFactory $categorySetupFactory)
    {
        $this->categorySetupFactory = $categorySetupFactory;
    }
    
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        
        $installer->startSetup();
        if (version_compare($context->getVersion(), '1.1.0', '<=')) {
            $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);
            $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Category::ENTITY);
            $attributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId);
            $menu_attributes = [
                'zozo_custom_url' => [
                    'type' => 'varchar',
                    'label' => 'Custom Url',
                    'input' => 'text',
                    'required' => false,
                    'sort_order' => 35,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'group' => 'ZOZO Menu'
                ]
            ];
        
            foreach($menu_attributes as $item => $data) {
                $categorySetup->addAttribute(\Magento\Catalog\Model\Category::ENTITY, $item, $data);
            }
            $categorySetup->addAttributeGroup($entityTypeId, $attributeSetId, 'ZOZO Menu','1000');
        }
        
        $installer->endSetup();
    }
}