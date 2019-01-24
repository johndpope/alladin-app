<?php
/**
 * Zozoconcepts_Brands extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Zozoconcepts
 * @package        Zozoconcepts_Brands
 * @copyright      Copyright (c) 2014
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Brand - category controller
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Brands
 * @author      Zozoconcepts Hybrid
 */
require_once ("Mage/Adminhtml/controllers/Catalog/CategoryController.php");
class Zozoconcepts_Brands_Adminhtml_Brands_Brand_Catalog_CategoryController
    extends Mage_Adminhtml_Catalog_CategoryController {
    /**
     * construct
     * @access protected
     * @return void
     * @author Zozoconcepts Hybrid
     */
    protected function _construct(){
        // Define module dependent translate
        $this->setUsedModuleName('Zozoconcepts_Brands');
    }
    /**
     * brands grid in the catalog page
     * @access public
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function brandsgridAction(){
        $this->_initCategory();
        $this->loadLayout();
        $this->getLayout()->getBlock('category.edit.tab.brand')
            ->setCategoryBrands($this->getRequest()->getPost('category_brands', null));
        $this->renderLayout();
    }
}
