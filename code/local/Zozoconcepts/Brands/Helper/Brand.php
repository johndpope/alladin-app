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
 * Brand helper
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Brands
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Brands_Helper_Brand
    extends Mage_Core_Helper_Abstract {
    /**
     * get the url to the brands list page
     * @access public
     * @return string
     * @author Zozoconcepts Hybrid
     */
    public function getBrandsUrl(){
        return Mage::getUrl('zozoconcepts_brands/brand/index');
    }
    /**
     * check if breadcrumbs can be used
     * @access public
     * @return bool
     * @author Zozoconcepts Hybrid
     */
    public function getUseBreadcrumbs(){
        return Mage::getStoreConfigFlag('zozoconcepts_brands/brand/breadcrumbs');
    }
    /**
     * get base files dir
     * @access public
     * @return string
     * @author Zozoconcepts Hybrid
     */
    public function getFileBaseDir(){
        return Mage::getBaseDir('media').DS.'brand'.DS.'file';
    }
    /**
     * get base file url
     * @access public
     * @return string
     * @author Zozoconcepts Hybrid
     */
    public function getFileBaseUrl(){
        return Mage::getBaseUrl('media').'brand'.'/'.'file';
    }
}
