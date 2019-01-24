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
 * Brand list block
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Brands
 * @author Zozoconcepts Hybrid
 */
class Zozoconcepts_Brands_Block_Brand_List
    extends Mage_Core_Block_Template {
    /**
     * initialize
     * @access public
     * @author Zozoconcepts Hybrid
     */
     public function __construct(){
        parent::__construct();
         $brands = Mage::getResourceModel('zozoconcepts_brands/brand_collection')
                         ->addStoreFilter(Mage::app()->getStore())
                         ->addFieldToFilter('status', 1);
        $brands->setOrder('title', 'asc');
        $this->setBrands($brands);
    }
    /**
     * prepare the layout
     * @access protected
     * @return Zozoconcepts_Brands_Block_Brand_List
     * @author Zozoconcepts Hybrid
     */
    protected function _prepareLayout(){
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock('page/html_pager', 'zozoconcepts_brands.brand.html.pager')
            ->setCollection($this->getBrands());
        $this->setChild('pager', $pager);
        $this->getBrands()->load();
        return $this;
    }
    /**
     * get the pager html
     * @access public
     * @return string
     * @author Zozoconcepts Hybrid
     */
    public function getPagerHtml(){
        return $this->getChildHtml('pager');
    }
}
