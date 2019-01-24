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

class Zozoconcepts_Catalogcategorysearch_Block_Type extends Mage_Core_Block_Template
{
	
	public function block_type()
		{

			$helper = $this->helper('catalogcategorysearch');
			$block_type = $helper->blocktype(); 
			
			// set templates as per config selection
				if($block_type == "2")
				{
					$settemplate = $this->getLayout()
									->createBlock('catalogcategorysearch/form')
									->setTemplate('zozoconcepts/zozoconcepts_catalogsearch/form.phtml')
									->toHtml();
				}
				elseif($block_type == "1")
				{
					$settemplate = $this->getLayout()
									->createBlock('core/template')
									->setTemplate('catalogsearch/form.mini.phtml')
									->toHtml();			
				}
				else{}
				return $settemplate;
			
			
		}
}
