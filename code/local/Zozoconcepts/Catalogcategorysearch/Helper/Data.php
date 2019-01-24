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

class Zozoconcepts_Catalogcategorysearch_Helper_Data extends Mage_Core_Helper_Abstract
{
	const XML_PATH_SEARCH_BLOCK_TYPE				 = 'hybrid/header/search_block_type';
    const XML_PATH_SHOW_SUBCATEGORIES                = 'hybrid/header/show_subcategories';
    const XML_PATH_INDENTATION_TEXT                  = 'hybrid/header/indentation_text';
    const XML_PATH_SELECT_CATEGORY_ON_CATEGORY_PAGES = 'hybrid/header/select_category_on_category_pages';

	public function blocktype() {
        return Mage::getStoreConfig(self::XML_PATH_SEARCH_BLOCK_TYPE);
    }
    public function showSubCategories() {
        return Mage::getStoreConfig(self::XML_PATH_SHOW_SUBCATEGORIES);
    }

    public function getIndentationText() {
        return Mage::getStoreConfig(self::XML_PATH_INDENTATION_TEXT);
    }

    public function selectCategoryOnCategoryPages() {
        return Mage::getStoreConfig(self::XML_PATH_SELECT_CATEGORY_ON_CATEGORY_PAGES);
    }

    public function getCategoryParamName() {
        return Mage::getModel('catalog/layer_filter_category')->getRequestVar();
    }

    public function getMaximumCategoryLevel() {
        return $this->showSubCategories() ? 3 : 2;
    }

    public function isCategoryPage() {
        return Mage::app()->getFrontController()->getAction() instanceof Mage_Catalog_CategoryController;
    }

    public function isSearchResultsPage() {
        return Mage::app()->getFrontController()->getAction() instanceof Mage_CatalogSearch_ResultController;
    }

}
