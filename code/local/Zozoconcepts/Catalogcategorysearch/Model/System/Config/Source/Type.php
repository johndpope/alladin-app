<?php
/**
 * Zozoconcepts Catalog Category Search
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Catalogcategorysearch
 * @copyright	Copyright (c) 2014
 * @license		http://opensource.org/licenses/mit-license.php MIT License
 */

class Zozoconcepts_Catalogcategorysearch_Model_System_Config_Source_Type extends Mage_Core_Block_Template
{
	public function toOptionArray()
    {
		return array(
			array('value' => '1',	'label' => Mage::helper('catalogcategorysearch')->__('Default')),
			array('value' => '2',	'label' => Mage::helper('catalogcategorysearch')->__('Search Category type')),
        );
    }
}
