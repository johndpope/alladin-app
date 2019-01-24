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
 * Brand image field renderer helper
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Brands
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Brands_Block_Adminhtml_Brand_Helper_Image
    extends Varien_Data_Form_Element_Image {
    /**
     * get the url of the image
     * @access protected
     * @return string
     * @author Zozoconcepts Hybrid
     */
    protected function _getUrl(){
        $url = false;
        if ($this->getValue()) {
            $url = Mage::helper('zozoconcepts_brands/brand_image')->getImageBaseUrl().$this->getValue();
        }
        return $url;
    }
}
