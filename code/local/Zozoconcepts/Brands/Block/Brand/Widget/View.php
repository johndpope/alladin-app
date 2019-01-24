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
 * Brand widget block
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Brands
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Brands_Block_Brand_Widget_View
    extends Mage_Core_Block_Template
    implements Mage_Widget_Block_Interface {
    protected $_htmlTemplate = 'zozoconcepts_brands/brand/widget/view.phtml';
    /**
     * Prepare a for widget
     * @access protected
     * @return Zozoconcepts_Brands_Block_Brand_Widget_View
     * @author Zozoconcepts Hybrid
     */
    protected function _beforeToHtml() {
        parent::_beforeToHtml();
        $brandId = $this->getData('brand_id');
        if ($brandId) {
            $brand = Mage::getModel('zozoconcepts_brands/brand')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($brandId);
            if ($brand->getStatus()) {
                $this->setCurrentBrand($brand);
                $this->setTemplate($this->_htmlTemplate);
            }
        }
        return $this;
    }
}
